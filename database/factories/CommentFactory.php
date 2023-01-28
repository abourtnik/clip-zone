<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;
use App\Models\Comment;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        $date = fake()->dateTimeBetween('-1 year');
        $user = User::inRandomOrder()->first();

        return [
            'content' => fake()->realText(5000),
            'user_id' => $user->id,
            'ip' => fake()->ipv4(),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
