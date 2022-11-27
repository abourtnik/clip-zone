<?php

namespace Database\Factories;

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
            'title' => fake()->sentence(3, true),
            'description' => fake()->realTextBetween(10, 1000),
            'file' => '1.webm',
            'thumbnail' => fake()->numberBetween(1, 12) .'.png',
            'duration' => fake()->numberBetween(5, 4000),
            'mimetype' => 'video/mp4',
            'views' => fake()->numberBetween(10, 100000),
            'status' => fake()->numberBetween(0, 1),
            'publication_date' => fake()->dateTimeInInterval('now', '+3 days')
        ];
    }
}
