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

        return [
            'content' => fake()->realText(config('validation.comment.content.max')),
            'user_id' => User::inRandomOrder()->first()->id,
            'ip' => fake()->ipv4(),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
