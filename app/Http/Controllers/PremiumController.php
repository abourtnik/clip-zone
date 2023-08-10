<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Support\Facades\Auth;

class PremiumController extends Controller
{
    public function subscribe (Plan $plan)  {
        return Auth::user()->newSubscription($plan->id, $plan->stripe_id)->checkout([
            'success_url' => route('pages.premium', ['success' => true]),
            'cancel_url' => route('pages.premium'),
            'billing_address_collection' => 'required',
            'automatic_tax' => [
                'enabled' => true
            ]
        ]);
    }
}
