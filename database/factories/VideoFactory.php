<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'uuid' => fake()->uuid(),
            'title' => fake()->realText(100),
            'description' => fake()->realText(5000),
            'file' => '1.webm',
            'original_file_name' => fake()->word() . '.avi',
            'thumbnail' => fake()->numberBetween(1, 12) .'.png',
            'duration' => fake()->numberBetween(5, 4000),
            'mimetype' => 'video/mp4',
            'status' => fake()->numberBetween(0, 3),
            'publication_date' => fake()->dateTimeBetween('-1 year'),
            'category_id' => Category::inRandomOrder()->first()->id,
            'language' => fake()->languageCode(),
            'allow_comments' => fake()->boolean(90),
            'default_comments_sort' => fake()->randomElement(['top', 'newest']),
            'show_likes' => fake()->boolean(90),
        ];
    }
}
