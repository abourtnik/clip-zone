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
        $response = $this->get(route('pages.home'));

        $response->assertStatus(200);
    }

    public function test_trend(): void
    {
        $response = $this->get(route('pages.trend'));

        $response->assertStatus(200);
    }

    public function test_private_video_show(): void
    {
        $video = Video::factory()->withStatus(VideoStatus::PRIVATE->value)->forUser()->create();

        $response = $this->get($video->route);

        $response->assertStatus(404);
    }

    public function test_planned_video_show(): void
    {
        $video = Video::factory()->withStatus(VideoStatus::PLANNED->value)->forUser()->create([
            'scheduled_date' => now()->addMinutes(30)
        ]);

        $response = $this->get($video->route);

        $response->assertStatus(404);
    }

    public function test_planned_public_video_show(): void
    {
        $video = Video::factory()->withStatus(VideoStatus::PLANNED->value)->forUser()->create([
            'scheduled_date' => now()->subMinutes(30)
        ]);

        $response = $this->get($video->route);

        $response->assertStatus(200);
    }


    public function test_video_show(): void
    {
        $video = Video::factory()->withStatus(VideoStatus::PUBLIC->value)->forUser()->create();

        $response = $this->get($video->route);

        $response->assertStatus(200);
    }

    public function test_user_show(): void
    {
        $user = User::factory()->create();

        $response = $this->get($user->route);

        $response->assertStatus(200);
    }
}
