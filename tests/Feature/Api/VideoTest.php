<?php

namespace Tests\Feature\Api;

use App\Enums\VideoStatus;
use App\Models\Thumbnail;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class VideoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_videos_index(): void
    {
        $videos = Video::factory(10)
            ->withStatus(VideoStatus::PUBLIC)
            ->forUser()
            ->has(
                Thumbnail::factory()
                    ->generated()
                    ->count(3)
            )
            ->create()
            ->each(function($video) {
                Thumbnail::factory(3)
                    ->generated()
                    ->for($video)
                    ->create();
            });

        $response = $this->get(route('videos.index'));

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json
                ->has('data', $videos->count(), fn (AssertableJson $json) =>
                $json
                    ->whereType('id', 'integer')
                    ->whereType('uuid', 'string')
                    ->whereType('title', 'string')
                    ->whereType('short_title', 'string')
                    ->whereType('thumbnail', 'string')
                    ->whereType('formated_duration', 'string')
                    ->whereType('views', 'integer')
                    ->whereType('route', 'string')
                    ->whereType('published_at', 'string')
                    ->has('user', fn (AssertableJson $json) =>
                        $json->whereType('id', 'integer')
                            ->whereType('username', 'string')
                            ->whereType('slug', 'string')
                            ->whereType('avatar', 'string')
                            ->whereType('route', 'string')
                            ->whereType('show_subscribers', 'boolean')
                            ->whereType('created_at', 'string')
                    )
                )
                ->has('links')
                ->has('meta')
            );
    }

    public function test_videos_trend(): void
    {
        $videos = Video::factory(10)
            ->withStatus(VideoStatus::PUBLIC)
            ->forUser()
            ->has(
                Thumbnail::factory()
                    ->generated()
                    ->count(3)
            )
            ->create()
            ->each(function($video) {
                Thumbnail::factory(3)
                    ->generated()
                    ->for($video)
                    ->create();
            });

        $response = $this->get(route('videos.trend'));

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json
                ->has('data', $videos->count(), fn (AssertableJson $json) =>
                $json
                    ->whereType('id', 'integer')
                    ->whereType('uuid', 'string')
                    ->whereType('title', 'string')
                    ->whereType('short_title', 'string')
                    ->whereType('thumbnail', 'string')
                    ->whereType('formated_duration', 'string')
                    ->whereType('views', 'integer')
                    ->whereType('route', 'string')
                    ->whereType('published_at', 'string')
                    ->has('user', fn (AssertableJson $json) =>
                    $json->whereType('id', 'integer')
                        ->whereType('username', 'string')
                        ->whereType('slug', 'string')
                        ->whereType('avatar', 'string')
                        ->whereType('route', 'string')
                        ->whereType('show_subscribers', 'boolean')
                        ->whereType('created_at', 'string')
                    )
                )
                ->has('links')
                ->has('meta')
            );
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_video_show_public(): void
    {
        $video = Video::factory()
            ->withStatus(VideoStatus::PUBLIC)
            ->forUser()
            ->has(
                Thumbnail::factory()
                    ->generated()
                    ->count(3)
            )
            ->create();

        $response = $this->get('/api/videos/'.$video->uuid);

        $response
            ->assertStatus(200)
            ->assertJsonIsObject()
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->whereType('id', 'integer')
                    ->whereType('uuid', 'string')
                    ->whereType('file', 'string')
                    ->whereType('allow_comments', 'boolean')
                    ->whereType('show_likes', 'boolean')
                    ->when($video->show_likes, fn() =>
                        $json->whereType('likes', 'integer')
                            ->whereType('dislikes', 'integer')
                    )
                    ->whereType('default_comments_sort', 'string')
                    ->whereType('title', 'string')
                    ->whereType('short_title', 'string')
                    ->whereType('short_description', 'string')
                    ->whereType('description', 'string')
                    ->whereType('description_is_long', 'boolean')
                    ->whereType('thumbnail', 'string')
                    ->whereType('duration', 'integer')
                    ->whereType('formated_duration', 'string')
                    ->whereType('views', 'integer')
                    ->whereType('route', 'string')
                    ->has('user', fn (AssertableJson $json) =>
                        $json->whereType('id', 'integer')
                            ->whereType('username', 'string')
                            ->whereType('slug', 'string')
                            ->whereType('avatar', 'string')
                            ->whereType('route', 'string')
                            ->whereType('show_subscribers', 'boolean')
                            ->whereType('created_at', 'string')
                            ->etc()
                    )
                    ->whereType('comments', 'integer')
                    ->whereType('published_at', 'string')
                    ->whereType('status', 'integer')
                    ->whereType('first_comment', 'string|null')
                    ->whereType('suggested', 'array')
            );
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_video_show_private(): void
    {
        $video = Video::factory()
            ->withStatus(VideoStatus::PRIVATE)
            ->forUser()
            ->create();

        $response = $this->get('/api/videos/'.$video->uuid);

        $response->assertNotFound();
    }
}
