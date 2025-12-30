<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MemberMembership;
use App\Models\MembershipPlan;
use App\Models\PricingSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;


class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        // If you already protect routes with middleware, you can remove this.
        // abort_unless(auth()->check() && auth()->user()->role === 'administrator', 403);

        $subscriptions = MemberMembership::with(['member', 'plan'])
            ->orderByDesc('id')
            ->get();

        $pricingSetting = PricingSetting::query()->first();
        $monthlyPrice = $pricingSetting?->monthly_subscription_price;

        $today = Carbon::today();

        return response()->json([
            'subscriptions' => $subscriptions->map(function (MemberMembership $subscription) use ($monthlyPrice, $today) {
                $durationDays = $subscription->plan?->duration_days ?? 0;
                $months = $durationDays > 0 ? max(1, (int) ceil($durationDays / 30)) : 0;
                $price = $monthlyPrice ? $monthlyPrice * $months : null;

                $isExpired = $subscription->is_expired || ($subscription->end_date && $subscription->end_date->lt($today));
                $status = $subscription->is_on_hold ? 'On Hold' : ($isExpired ? 'Expired' : 'Active');

                return [
                    'id' => $subscription->id,
                    'member_name' => $subscription->member?->name ?? 'Unknown',
                    'plan_name' => $subscription->plan?->name ?? 'Plan',
                    'duration_days' => $durationDays,
                    'price' => $price,
                    'start_date' => optional($subscription->start_date)->toDateString(),
                    'end_date' => optional($subscription->end_date)->toDateString(),
                    'is_on_hold' => (bool) $subscription->is_on_hold,
                    'status' => $status,
                ];
            }),
        ]);
    }

    public function options()
{

        $members = User::query()
            ->where('role', 'user')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $plans = MembershipPlan::query()
            ->where('is_active', true)
            ->orderBy('duration_days')
            ->get(['id', 'name', 'duration_days']);

        return response()->json([
            'members' => $members,
            'plans' => $plans,
        ]);

}

    public function store(Request $request)
    {
        $data = $request->validate([
        'member_id' => [
        'required',
            Rule::exists('users', 'id')->where(fn ($q) => $q->where('role', 'user')),
            ],
            'membership_plan_id' => ['required', 'exists:membership_plans,id'],
            'start_date' => ['nullable', 'date'],
        ]);

        $plan = MembershipPlan::query()->findOrFail($data['membership_plan_id']);

        $startDate = isset($data['start_date'])
            ? Carbon::parse($data['start_date'])
            : Carbon::today();

        $endDate = ($plan->duration_days ?? 0) > 0
            ? $startDate->copy()->addDays((int) $plan->duration_days)
            : $startDate->copy();

        $subscription = MemberMembership::create([
            'member_id' => $data['member_id'], // ✅ user id
            'membership_plan_id' => $plan->id,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'is_expired' => false,
            'is_on_hold' => false,
            'hold_started_at' => null,
        ]);

        return response()->json([
            'message' => 'Subscription created successfully.',
            'subscription_id' => $subscription->id,
        ], Response::HTTP_CREATED);
    }

    public function hold(MemberMembership $subscription)
    {
        $today = Carbon::today();

        if ($subscription->is_expired || ($subscription->end_date && $subscription->end_date->lt($today))) {
            return response()->json([
                'message' => 'Expired subscriptions cannot be placed on hold.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($subscription->is_on_hold) {
            return response()->json([
                'message' => 'Subscription is already on hold.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $subscription->update([
            'is_on_hold' => true,
            'hold_started_at' => $today,
        ]);

        return response()->json([
            'message' => 'Subscription placed on hold.',
        ]);
    }

    public function resume(MemberMembership $subscription)
    {
        if (! $subscription->is_on_hold) {
            return response()->json([
                'message' => 'Subscription is not on hold.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $today = Carbon::today();
        $holdStartedAt = $subscription->hold_started_at ? Carbon::parse($subscription->hold_started_at) : $today;
        $holdDays = max(0, $holdStartedAt->diffInDays($today));

        if ($subscription->end_date) {
            $subscription->end_date = Carbon::parse($subscription->end_date)->addDays($holdDays);
        }

        $subscription->is_on_hold = false;
        $subscription->hold_started_at = null;
        $subscription->is_expired = false;
        $subscription->save();

        return response()->json([
            'message' => 'Subscription resumed.',
        ]);
    }
}
