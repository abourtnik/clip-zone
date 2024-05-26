<?php

namespace Database\Factories;

use App\Enums\VideoStatus;
use App\Helpers\Number;
use App\Models\Category;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $date = fake()->dateTimeBetween('-1 year');

        $title = fake()->text(rand(5, config('validation.video.title.max')));

        return [
            'uuid' => Number::unique(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->realTextBetween(100, config('validation.video.description.max')),
            'file' => 'default.webm',
            'original_file_name' => fake()->word() . '.avi',
            'duration' => fake()->numberBetween(5, 4000),
            'mimetype' => 'video/mp4',
            'status' => fake()->randomElement([VideoStatus::PUBLIC->value, VideoStatus::PRIVATE->value, VideoStatus::UNLISTED->value]),
            'publication_date' => $date,
            'category_id' => Category::inRandomOrder()->first()?->id,
            'language' => fake()->randomElement(Video::AVAILABLE_LANGUAGES),
            'allow_comments' => fake()->boolean(90),
            'default_comments_sort' => fake()->randomElement(['top', 'newest']),
            'show_likes' => fake()->boolean(90),
            'created_at' => $date,
            'updated_at' => $date,
            'uploaded_at' => $date
        ];
    }

    /**
     * Indicate that the comment is banned.
     *
     * @return Factory
     */
    public function banned() : Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'banned_at' => now(),
            ];
        });
    }
}
