<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ViewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ip' => fake()->ipv4(),
            'view_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'user_id' => User::inRandomOrder()->first()->id
        ];
    }
}