<?php

namespace Database\Factories\Pivots;

use App\Models\Pivots\Subscription;
use App\Models\User;
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
        $date = fake()->dateTimeBetween('-1 year');

        return [
            'subscribe_at' => $date,
            'read_at' => null
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->sequence(
            fn ($sequence) => ['user_id' => fake()->unique($sequence->index === 0)->randomElement(User::pluck('id'))]
        );
    }
}
