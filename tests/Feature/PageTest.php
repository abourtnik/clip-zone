<?php

namespace Tests\Feature;

use App\Enums\VideoStatus;
use App\Models\Video;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_home(): void
    {
        $this
            ->get(route('pages.home'))
            ->assertStatus(200);
    }

    public function test_trend(): void
    {
        $this
            ->get(route('pages.trend'))
            ->assertStatus(200);
    }

    public function test_private_video_show(): void
    {
        $video = Video::factory()
            ->forUser()
            ->withStatus(VideoStatus::PRIVATE)
            ->create();

        $this
            ->get($video->route)
            ->assertStatus(404);
    }

    public function test_planned_video_show(): void
    {
        $video = Video::factory()
            ->forUser()
            ->withStatus(VideoStatus::PLANNED)
            ->create([
                'scheduled_at' => now()->addMinutes(30)
            ]);

        $this
            ->get($video->route)
            ->assertStatus(404);
    }

    public function test_planned_public_video_show(): void
    {
        $video = Video::factory()
            ->forUser()
            ->withStatus(VideoStatus::PLANNED)
            ->create([
                'scheduled_at' => now()->subMinutes(30)
            ]);

        $this
            ->get($video->route)
            ->assertStatus(200);
    }

    public function test_deleted_video_show(): void
    {
        $video = Video::factory()
            ->forUser()
            ->withStatus(VideoStatus::PUBLIC)
            ->create([
                'deleted_at' => now()->subMinutes(30)
            ]);

        $this
            ->get($video->route)
            ->assertStatus(404);
    }


    public function test_video_show(): void
    {
        $video = Video::factory()
            ->forUser()
            ->withStatus(VideoStatus::PUBLIC)
            ->create();

        $this
            ->get($video->route)
            ->assertStatus(200);
    }

    public function test_user_show(): void
    {
        $user = User::factory()->create();

        $this
            ->get($user->route)
            ->assertStatus(200);
    }
}
