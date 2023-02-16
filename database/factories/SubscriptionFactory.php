<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Subscription;

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
        $date = fake()->dateTimeBetween('-1 year');

        return [
            'subscribe_at' => $date,
            'read_at' => $date,
        ];
    }
}
