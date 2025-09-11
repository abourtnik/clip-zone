<?php

namespace Tests\Feature;

use App\Enums\VideoStatus;
use App\Models\User;
use App\Models\Video;
use App\Notifications\Account\VerifyUpdatedEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user);
    }
    public function test_update_email() :void
    {
        Notification::fake();

        $this->fromRoute('user.edit')->put(route('user.update'), [
            'email' => 'new_email@example.com',
        ])->assertRedirectToRoute('user.edit');

        Notification::assertSentTo(
            [$this->user], VerifyUpdatedEmail::class
        );
    }

    public function test_dashboard_page ()
    {
        $response = $this->get(route('user.index'));

        $response->assertStatus(200);
    }

    public function test_videos_page ()
    {
        $response = $this->get(route('user.videos.index'));

        $response->assertStatus(200);
    }

    public function test_videos_create_page ()
    {
        $video = Video::factory()
            ->for($this->user)
            ->withStatus(VideoStatus::DRAFT)
            ->create();

        $response = $this->get(route('user.videos.create', $video));

        $response->assertStatus(200);
    }

    public function test_videos_edit_page ()
    {
        $video = Video::factory()
            ->for($this->user)
            ->withStatus(VideoStatus::PUBLIC)
            ->create();

        $response = $this->get(route('user.videos.edit', $video));

        $response->assertStatus(200);
    }

    public function test_playlist_page ()
    {
        $response = $this->get(route('user.videos.index'));

        $response->assertStatus(200);
    }

    public function test_subscribers_page ()
    {
        $response = $this->get(route('user.subscribers'));

        $response->assertStatus(200);
    }

    public function test_comments_page ()
    {
        $response = $this->get(route('user.comments.index'));

        $response->assertStatus(200);
    }

    public function test_subtitles_page ()
    {
        $response = $this->get(route('user.subtitles.list'));

        $response->assertStatus(200);
    }

    public function test_activity_page ()
    {
        $response = $this->get(route('user.activity.index'));

        $response->assertStatus(200);
    }

    public function test_reports_page ()
    {
        $response = $this->get(route('user.reports.index'));

        $response->assertStatus(200);
    }

    public function test_invoices_page ()
    {
        $response = $this->get(route('user.invoices.index'));

        $response->assertStatus(200);
    }

    public function test_profile_page ()
    {
        $response = $this->get(route('user.edit'));

        $response->assertStatus(200);
    }
}
