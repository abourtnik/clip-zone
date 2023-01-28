<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\View;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<View>
 */
class ViewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'ip' => fake()->ipv4(),
            'view_at' => fake()->dateTimeBetween('-1 year'),
            'user_id' => User::inRandomOrder()->first()->id
        ];
    }
}
