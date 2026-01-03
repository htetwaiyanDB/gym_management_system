<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserMessageController extends Controller
{
    public function messages(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role === 'administrator') {
            return response()->json([
                'message' => 'Administrators cannot access this endpoint.',
            ], 403);
        }

        $admin = $this->adminUser();

        if (! $admin) {
            return response()->json([
                'message' => 'Administrator account not found.',
            ], 404);
        }

        $messages = Message::query()
            ->with('sender')
            ->where(function ($query) use ($user, $admin) {
                $query->where('sender_id', $user->id)
                    ->where('recipient_id', $admin->id);
            })
            ->orWhere(function ($query) use ($user, $admin) {
                $query->where('sender_id', $admin->id)
                    ->where('recipient_id', $user->id);
            })
            ->orderBy('created_at')
            ->get();

        Message::where('sender_id', $admin->id)
            ->where('recipient_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
            ],
            'messages' => $messages->map(function (Message $message) use ($user) {
                return [
                    'id' => $message->id,
                    'body' => $message->body,
                    'created_at' => $message->created_at?->toIso8601String(),
                    'is_sender' => $message->sender_id === $user->id,
                    'sender_name' => $message->sender?->name,
                ];
            }),
        ]);
    }

    public function sendMessage(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role === 'administrator') {
            return response()->json([
                'message' => 'Administrators cannot access this endpoint.',
            ], 403);
        }

        $admin = $this->adminUser();

        if (! $admin) {
            return response()->json([
                'message' => 'Administrator account not found.',
            ], 404);
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $message = Message::create([
            'sender_id' => $user->id,
            'recipient_id' => $admin->id,
            'body' => $validated['body'],
        ]);

        if ($this->shouldNotify($admin)) {
            $admin->notify(new NewMessageNotification($message, $user));
        }

        return response()->json([
            'status' => 'sent',
        ], 201);
    }

    private function adminUser(): ?User
    {
        return User::query()
            ->where('role', 'administrator')
            ->first();
    }

    private function shouldNotify(User $user): bool
    {
        return $user->role === 'administrator' || $user->notifications_enabled;
    }
}
