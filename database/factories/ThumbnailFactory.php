<?php

namespace Database\Factories;

use App\Enums\ThumbnailStatus;
use App\Models\Thumbnail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Thumbnail>
 */
class ThumbnailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'file' => fake()->regexify('^[a-zA-Z0-9]{40}\.jpg$'),
        ];
    }

    public function configure(): static
    {
        return $this->sequence(
            fn ($sequence) => [
                'is_active' => $sequence->index === 0,
            ]
        );
    }

    /**
     * Indicate that the thumbnail is generated.
     *
     * @return Factory
     */
    public function generated() : Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => ThumbnailStatus::GENERATED,
            ];
        });
    }

    /**
     * Indicate that the thumbnail is uploaded.
     *
     * @return Factory
     */
    public function uploaded() : Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => ThumbnailStatus::UPLOADED,
            ];
        });
    }

    /**
     * Indicate that the thumbnail is error.
     *
     * @return Factory
     */
    public function error() : Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => ThumbnailStatus::ERROR,
            ];
        });
    }

    /**
     * Indicate that the thumbnail is active.
     *
     * @return Factory
     */
    public function active() : Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => true
            ];
        });
    }


}
