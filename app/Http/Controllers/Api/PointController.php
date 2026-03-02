<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Point;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            'created_at' => $point->created_at?->toIso8601String(),
            'updated_at' => $point->updated_at?->toIso8601String(),
        ];
    }
}
