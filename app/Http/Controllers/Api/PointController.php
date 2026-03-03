<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Point;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class PointController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Point::query()
            ->with('user:id,name,role')
            ->orderByDesc('point');

        $user = $request->user();

        if ($user && $user->role !== 'administrator') {
            $query->where('user_id', $user->id);
        }

        $points = $query->get();

        return response()->json([
            'data' => $points->map(fn (Point $point) => $this->formatPoint($point)),
        ]);
    }

    public function show(Point $point): JsonResponse
    {
        $point->load('user:id,name,role');

        return response()->json([
            'data' => $this->formatPoint($point),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', Rule::exists('users', 'id'), Rule::unique('points', 'user_id')],
            'point' => ['required', 'integer', 'min:0'],
        ]);

        $point = Point::create($validated);
        $point->load('user:id,name,role');

        return response()->json([
            'message' => 'Point record created successfully.',
            'data' => $this->formatPoint($point),
        ], 201);
    }

    public function update(Request $request, Point $point): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'sometimes',
                'integer',
                Rule::exists('users', 'id'),
                Rule::unique('points', 'user_id')->ignore($point->id),
            ],
            'point' => ['sometimes', 'integer', 'min:0'],
        ]);

        $point->update($validated);
        $point->load('user:id,name,role');

        return response()->json([
            'message' => 'Point record updated successfully.',
            'data' => $this->formatPoint($point),
        ]);
    }

    public function adjust(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],
            'amount' => ['required', 'integer', 'not_in:0'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $point = DB::transaction(function () use ($validated) {
            $point = Point::query()
                ->where('user_id', $validated['user_id'])
                ->lockForUpdate()
                ->first();

            if (!$point) {
                $point = Point::create([
                    'user_id' => $validated['user_id'],
                    'point' => 0,
                ]);
            }

            $nextBalance = $point->point + $validated['amount'];

            if ($nextBalance < 0) {
                throw ValidationException::withMessages([
                    'amount' => ['Point balance cannot be negative.'],
                ]);
            }

            $point->forceFill([
                'point' => $nextBalance,
            ])->save();

            return $point;
        });

        $point->load('user:id,name,role');

        return response()->json([
            'message' => 'Point balance adjusted successfully.',
            'data' => $this->formatPoint($point),
            'meta' => [
                'adjustment_amount' => $validated['amount'],
                'reason' => $validated['reason'] ?? null,
                'adjusted_at' => Carbon::now()->toIso8601String(),
            ],
        ]);
    }


    public function destroy(Point $point): JsonResponse
    {
        $point->delete();

        return response()->json([
            'message' => 'Point record deleted successfully.',
        ]);
    }

    private function formatPoint(Point $point): array
    {
        return [
            'id' => $point->id,
            'user_id' => $point->user_id,
            'user_name' => $point->user?->name,
            'user_role' => $point->user?->role,
            'point' => $point->point,
            'last_daily_reward_date' => $point->last_daily_reward_date?->toDateString(),
            'created_at' => $point->created_at?->toIso8601String(),
            'updated_at' => $point->updated_at?->toIso8601String(),
        ];
    }
}
