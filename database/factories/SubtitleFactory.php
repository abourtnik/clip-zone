<?php

namespace Database\Factories;

use App\Enums\SubtitleStatus;
use App\Models\Subtitle;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subtitle>
 */
class SubtitleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'name' => fake()->text(50),
            'language' => fake()->randomElement(Video::AVAILABLE_LANGUAGES),
            'file' => 'default.vtt',
            'status' => SubtitleStatus::GENERATED
        ];
    }

    public function uploaded() : Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => SubtitleStatus::UPLOADED,
            ];
        });
    }
}
