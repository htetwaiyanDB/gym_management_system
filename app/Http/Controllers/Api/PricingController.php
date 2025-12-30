<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PricingSetting;
use App\Models\TrainerPricing;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function updateTrainer(Request $request, User $user): JsonResponse
    {
        if ($user->role !== 'trainer') {
            abort(404);
        }

        $validated = $request->validate([
            'price_per_session' => ['required', 'numeric', 'min:0'],
        ]);

        $trainerPricing = TrainerPricing::updateOrCreate(
            ['trainer_id' => $user->id],
            ['price_per_session' => $validated['price_per_session']]
        );

        return response()->json([
            'message' => "Session price updated for {$user->name}.",
            'trainer' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'pricing' => [
                'price_per_session' => $trainerPricing->price_per_session,
            ],
        ]);
    }

    /**
     * Update Monthly Subscription Price
     */
    public function updateMonthly(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'monthly_subscription_price' => ['required', 'numeric', 'min:0'],
        ]);

        $pricingSetting = PricingSetting::firstOrCreate(
            [],
            [
                'monthly_subscription_price' => 80000,
                'quarterly_subscription_price' => 400000,
                'annual_subscription_price' => 800000,
            ]
        );

        $pricingSetting->update($validated);

        return response()->json([
            'message' => 'Monthly subscription price updated.',
            'pricing' => [
                'monthly_subscription_price' => $pricingSetting->monthly_subscription_price,
            ],
        ]);
    }

    /**
     * Update Quarterly Subscription Price
     */
    public function updateQuarterly(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'quarterly_subscription_price' => ['required', 'numeric', 'min:0'],
        ]);

        $pricingSetting = PricingSetting::firstOrCreate(
            [],
            [
                'monthly_subscription_price' => 80000,
                'quarterly_subscription_price' => 240000,
                'annual_subscription_price' => 960000,
            ]
        );

        $pricingSetting->update($validated);

        return response()->json([
            'message' => 'Quarterly subscription price updated.',
            'pricing' => [
                'quarterly_subscription_price' => $pricingSetting->quarterly_subscription_price,
            ],
        ]);
    }

    /**
     * Update Annual Subscription Price
     */
    public function updateAnnual(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'annual_subscription_price' => ['required', 'numeric', 'min:0'],
        ]);

        $pricingSetting = PricingSetting::firstOrCreate(
            [],
            [
                'monthly_subscription_price' => 80000,
                'quarterly_subscription_price' => 240000,
                'annual_subscription_price' => 960000,
            ]
        );

        $pricingSetting->update($validated);

        return response()->json([
            'message' => 'Annual subscription price updated.',
            'pricing' => [
                'annual_subscription_price' => $pricingSetting->annual_subscription_price,
            ],
        ]);
    }
}
