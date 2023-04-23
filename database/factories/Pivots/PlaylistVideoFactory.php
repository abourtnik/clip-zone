<?php

namespace Database\Factories\Pivots;

use App\Models\Pivots\PlaylistVideo;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlaylistVideo>
 */
class PlaylistVideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            //'video_id' => Video::inRandomOrder()->first()->id,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->sequence(
            fn ($sequence) => [
                'position' => $sequence->index,
                'video_id' => fake()->unique($sequence->index === 0)->randomElement(Video::pluck('id'))
            ]
        );
    }
}
