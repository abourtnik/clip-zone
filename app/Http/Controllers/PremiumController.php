<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Support\Facades\Auth;

class PremiumController extends Controller
{
    public function subscribe (Plan $plan)  {
        return Auth::user()->newSubscription($plan->id, $plan->stripe_id)
            ->trialDays(config('plans.trial_period.period'))
            ->checkout([
                'success_url' => route('pages.home', ['success' => true]),
                'cancel_url' => route('pages.premium'),
                'billing_address_collection' => 'required',
                'automatic_tax' => [
                    'enabled' => true
                ]
        ]);
    }
}
