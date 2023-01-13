<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->realText(100),
            'description' => fake()->realText(5000),
            'file' => '1.webm',
            'thumbnail' => fake()->numberBetween(1, 12) .'.png',
            'duration' => fake()->numberBetween(5, 4000),
            'mimetype' => 'video/mp4',
            'status' => fake()->numberBetween(0, 1),
            'publication_date' => fake()->dateTimeInInterval('now', '+3 days'),
            'category_id' => Category::inRandomOrder()->first()->id,
            'language' => fake()->languageCode(),
            'allow_comments' => fake()->boolean(),
            'default_comments_sort' => fake()->randomElement(['top', 'newest']),
            'show_likes' => fake()->boolean(),
        ];
    }
}
