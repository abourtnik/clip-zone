<?php

namespace Database\Factories;

use App\Models\Subscription;
use Stripe\Subscription as StripeSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'stripe_status' => StripeSubscription::STATUS_ACTIVE,
            'next_payment' => fake()->dateTimeThisYear(),
            'stripe_id' => fake()->bothify('sub_#???????????????????????'),
            'trial_ends_at' => fake()->dateTimeThisYear(),
            'card_last4' => fake()->randomNumber('4', true),
            'card_expired_at' => fake()->creditCardExpirationDate(),
        ];
    }
}
