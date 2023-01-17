<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;
use App\Models\Interaction;

/**
 * @extends Factory<Interaction>
 */
class InteractionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'status' => fake()->boolean(),
            'user_id' => User::inRandomOrder()->first()->id,
            'perform_at' => fake()->dateTimeBetween('-1 year'),
        ];
    }
}
