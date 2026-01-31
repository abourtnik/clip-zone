<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'name' => fake()->word(),
            'price' => fake()->randomElement([500, 5000]),
            'duration' => fake()->randomElement([1, 12]),
            'stripe_id' => fake()->bothify('price_#?#??#???????????#??##??')
        ];
    }
}
