<?php

namespace Database\Factories;

use App\Enums\PlaylistStatus;
use App\Models\Playlist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Playlist>
 */
class PlaylistFactory extends Factory
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
            'uuid' => fake()->uuid(),
            'title' => fake()->text(rand(5, config('validation.playlist.title.max'))),
            'description' => fake()->realText(config('validation.playlist.description.max')),
            'status' => fake()->randomElement([PlaylistStatus::PUBLIC->value, PlaylistStatus::PRIVATE->value, PlaylistStatus::UNLISTED->value]),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
