<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class PremiumController extends Controller
{
    public function subscribe (Plan $plan)  {

        $checkoutOptions = [
            'success_url' => route('pages.home', ['premium' => true]),
            'cancel_url' => route('pages.premium'),
            'billing_address_collection' => 'required',
            'automatic_tax' => [
                'enabled' => true
            ],
            'payment_method_types' => [
                'card',
            ],
            'payment_method_collection' => 'always',
            'subscription_data' => [
                'trial_period_days' => config('plans.trial_period.period')
            ]
        ];

        if (Auth::user()->premium_subscription) {
            Arr::forget($checkoutOptions, 'subscription_data');
        }

        return Auth::user()->newSubscription($plan->id, $plan->stripe_id)
            ->checkout($checkoutOptions);
    }
}
