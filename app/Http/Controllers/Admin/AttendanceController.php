<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceScan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->whereIn('role', ['user', 'trainer'])
            ->orderBy('name')
            ->get(['id', 'name', 'role']);

        $totalMembers = User::query()
            ->where('role', 'user')
            ->count();

        return view('pages.attendance', [
            'users' => $users,
            'totalMembers' => $totalMembers,
            'userQrData' => $this->qrUrl('user'),
            'trainerQrData' => $this->qrUrl('trainer'),
        ]);
    }

    public function records(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'username' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);

        $query = AttendanceScan::query()
            ->with('user')
            ->orderByDesc('scanned_at');

        if (!empty($validated['username'])) {
            $query->whereHas('user', function ($builder) use ($validated) {
                $builder->where('name', 'like', '%' . $validated['username'] . '%');
            });
        }

        if (!empty($validated['start_date'])) {
            $query->where('scanned_at', '>=', Carbon::parse($validated['start_date'])->startOfDay());
        }

        if (!empty($validated['end_date'])) {
            $query->where('scanned_at', '<=', Carbon::parse($validated['end_date'])->endOfDay());
        }

        $records = $query->get();

        $workingDays = $records
            ->groupBy('user_id')
            ->map(function ($userRecords) {
                $user = $userRecords->first()?->user;
                if (!$user || $user->role !== 'trainer') {
                    return '-';
                }

                return $userRecords
                    ->groupBy(fn ($record) => $record->scanned_at->toDateString())
                    ->filter(function ($dayRecords) {
                        return $dayRecords->pluck('action')->unique()->count() >= 2;
                    })
                    ->count();
            });

        $payload = $records->map(function ($record) use ($workingDays) {
            return [
                'username' => $record->user?->name ?? 'Unknown',
                'role' => $record->user?->role ?? 'user',
                'action' => $record->action,
                'timestamp' => $record->scanned_at->toIso8601String(),
                'total_check_in_days' => $workingDays[$record->user_id] ?? '-',
            ];
        });

        return response()->json([
            'records' => $payload,
        ]);
    }

    public function checkedIn(): JsonResponse
    {
        $today = Carbon::today();

        $todayScans = AttendanceScan::query()
            ->with('user')
            ->whereDate('scanned_at', $today)
            ->orderBy('scanned_at')
            ->get();

        $latestByUser = $todayScans
            ->groupBy('user_id')
            ->map(fn ($records) => $records->last());

        $activeUsers = $latestByUser
            ->filter(fn ($record) => $record && $record->action === 'check_in')
            ->values()
            ->map(function ($record) {
                return [
                    'username' => $record->user?->name ?? 'Unknown',
                    'role' => $record->user?->role ?? 'user',
                    'last_scan' => $record->scanned_at->toIso8601String(),
                    'status' => 'Active',
                ];
            });

        $totalMembers = User::query()
            ->where('role', 'user')
            ->count();

        return response()->json([
            'total_members' => $totalMembers,
            'active_count' => $activeUsers->count(),
            'active_users' => $activeUsers,
        ]);
    }

    public function scan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->whereIn('role', ['user', 'trainer'])),
            ],
            'qr_type' => ['required', Rule::in(['user', 'trainer'])],
        ]);

        $user = User::query()->findOrFail($validated['user_id']);

        if ($user->role !== $validated['qr_type']) {
            return response()->json([
                'message' => 'Selected user role does not match the QR code type.',
            ], 422);
        }

        $scan = $this->recordScan($user);

        return response()->json([
            'message' => 'Scan recorded successfully.',
            'record' => [
                'username' => $user->name,
                'role' => $user->role,
                'action' => $scan->action,
                'timestamp' => $scan->scanned_at->toIso8601String(),
            ],
        ]);
    }

    public function scanFromQr(Request $request)
    {
        $type = $request->query('type');
        $token = $request->query('token');

        if (!in_array($type, ['user', 'trainer'], true)) {
            return $this->scanErrorResponse($request, 'Invalid QR code type.');
        }

        if (!$this->tokenMatches($type, $token)) {
            return $this->scanErrorResponse($request, 'This QR code has expired. Please refresh and try again.');
        }

        $user = $request->user();

        if (!$user || !in_array($user->role, ['user', 'trainer'], true)) {
            return $this->scanErrorResponse($request, 'Unable to record attendance for this account.');
        }

        if ($user->role !== $type) {
            return $this->scanErrorResponse($request, 'Your account does not match this QR code type.');
        }

        $scan = $this->recordScan($user);

        $message = $scan->action === 'check_in'
            ? 'Check-in recorded successfully.'
            : 'Check-out recorded successfully.';

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'record' => [
                    'username' => $user->name,
                    'role' => $user->role,
                    'action' => $scan->action,
                    'timestamp' => $scan->scanned_at->toIso8601String(),
                ],
            ]);
        }

        return view('pages.attendance-scan', [
            'status' => 'success',
            'message' => $message,
            'action' => $scan->action,
            'timestamp' => $scan->scanned_at,
        ]);
    }

    public function refreshQr(): JsonResponse
    {
        $userToken = $this->getQrToken('user', true);
        $trainerToken = $this->getQrToken('trainer', true);

        return response()->json([
            'user_qr' => url('/attendance/scan?type=user&token=' . $userToken),
            'trainer_qr' => url('/attendance/scan?type=trainer&token=' . $trainerToken),
        ]);
    }

    private function recordScan(User $user): AttendanceScan
    {

        $lastScan = AttendanceScan::query()
            ->where('user_id', $user->id)
            ->whereDate('scanned_at', Carbon::today())
            ->orderByDesc('scanned_at')
            ->first();

        $nextAction = $lastScan && $lastScan->action === 'check_in'
            ? 'check_out'
            : 'check_in';

        return AttendanceScan::create([
            'user_id' => $user->id,
            'action' => $nextAction,
            'scanned_at' => Carbon::now(),
        ]);
    }

    private function qrUrl(string $type): string
    {
        $token = $this->getQrToken($type);

        return url('/attendance/scan?type=' . $type . '&token=' . $token);
    }

    private function getQrToken(string $type, bool $refresh = false): string
    {
        $key = $this->qrTokenKey($type);

        if ($refresh) {
            $token = Str::random(40);
            Cache::forever($key, $token);
            return $token;
        }

        return Cache::rememberForever($key, fn () => Str::random(40));
    }

    private function qrTokenKey(string $type): string
    {
        return 'attendance_qr_token_' . $type;
    }

    private function tokenMatches(string $type, ?string $token): bool
    {
        if (!$token) {
            return false;
        }

        $expected = Cache::get($this->qrTokenKey($type));

        return $expected && hash_equals($expected, $token);
    }

    private function scanErrorResponse(Request $request, string $message)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
            ], 422);
        }

        return view('pages.attendance-scan', [
            'status' => 'error',
            'message' => $message,
            'action' => null,
            'timestamp' => null,
        ]);
    }
}
