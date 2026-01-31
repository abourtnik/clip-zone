<?php

namespace Tests\Fixtures;

use App\Models\Subscription;
use Carbon\Carbon;
use Stripe\Subscription as StripeSubscription;
use App\Models\User;
use App\Models\Plan;

class StripeApiFixtures
{
    public static function customerSubscriptionCreated(string $subscriptionStripeId, User $user, Plan $plan, Carbon $date): array
    {
        return [
            'type' => 'customer.subscription.created',
            'data' => [
                'object' => [
                    'id' => $subscriptionStripeId,
                    'customer' => $user->stripe_id,
                    'plan' => [
                        'id' => $plan->stripe_id
                    ],
                    'current_period_end' => $date->timestamp,
                    'status' => StripeSubscription::STATUS_ACTIVE,
                    'trial_end' => $date->copy()->addMonth()->timestamp
                ]
            ]
        ];
    }

    public static function customerSubscriptionUpdated(Subscription $subscription, Carbon $date, bool $isCancelled = true): array
    {
        return [
            'type' => 'customer.subscription.updated',
            'data' => [
                'object' => [
                    'id' => $subscription->stripe_id,
                    'customer' => $subscription->user->stripe_id,
                    'plan' => [
                        'id' => $subscription->plan->stripe_id
                    ],
                    'current_period_end' => $date->timestamp,
                    'cancel_at_period_end' => $isCancelled,
                    'status' => StripeSubscription::STATUS_ACTIVE,
                ]
            ]
        ];
    }

    public static function invoicePaymentSucceeded(Subscription $subscription): array
    {
        return [
            'type' => 'invoice.payment_succeeded',
            'data' => [
                'object' => [
                    'customer' => $subscription->user->stripe_id,
                    'plan' => [
                        'id' => $subscription->plan->stripe_id
                    ],
                    'subscription' => $subscription->stripe_id,
                    'status' => StripeSubscription::STATUS_ACTIVE,
                    'payment_intent' => fake()->bothify('pi_????##???????'),
                    'tax' => fake()->randomNumber(2, true),
                    'created' => now()->timestamp,
                    'customer_name' => fake()->name(),
                    'customer_address' => [
                        'city' => fake()->city(),
                        'country' => fake()->countryCode(),
                        'line1' => fake()->address(),
                        'line2' => '',
                        'postal_code' => fake()->postcode()
                    ],
                    'customer_tax_ids' => [],
                    'amount_paid' => fake()->randomNumber(3, true),
                    'charge' => fake()->bothify('ch_????##???????')
                ]
            ]
        ];
    }
}
