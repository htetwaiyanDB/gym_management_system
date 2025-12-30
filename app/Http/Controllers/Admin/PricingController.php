<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingSetting;
use App\Models\TrainerPricing;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $trainers = User::query()
            ->where('role', 'trainer')
            ->orderBy('name')
            ->get();

        $pricingSetting = PricingSetting::firstOrCreate(
            [],
             [
                'monthly_subscription_price' => 80000,
                'quarterly_subscription_price' => 240000,
                'annual_subscription_price' => 960000,
            ]
        );

        $trainerPrices = TrainerPricing::query()
            ->whereIn('trainer_id', $trainers->pluck('id'))
            ->get()
            ->keyBy('trainer_id');

        return view('pages.pricing', [
            'trainers' => $trainers,
            'monthlyPrice' => $pricingSetting->monthly_subscription_price,
            'quarterlyPrice' => $pricingSetting->quarterly_subscription_price,
            'annualPrice' => $pricingSetting->annual_subscription_price,
            'defaultTrainerPrice' => 30000,
            'trainerPrices' => $trainerPrices,
        ]);
    }

    public function updateMonthly(Request $request): RedirectResponse
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

        return back()->with('status', 'Monthly subscription price updated.');
    }

    public function updateQuarterly(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'quarterly_subscription_price' => ['required', 'numeric', 'min:0'],
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

        return back()->with('status', 'Quarterly subscription price updated.');
    }

    public function updateAnnual(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'annual_subscription_price' => ['required', 'numeric', 'min:0'],
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

        return back()->with('status', 'Annual subscription price updated.');
    }


    public function updateTrainer(Request $request, User $user): RedirectResponse
    {
        if ($user->role !== 'trainer') {
            abort(404);
        }

        $validated = $request->validate([
            'price_per_session' => ['required', 'numeric', 'min:0'],
        ]);

        TrainerPricing::updateOrCreate(
            ['trainer_id' => $user->id],
            ['price_per_session' => $validated['price_per_session']]
        );

        return back()->with('status', "Session price updated for {$user->name}.");
    }
}
