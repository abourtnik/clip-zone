<?php

namespace Tests\Feature;

use App\Enums\ReportReason;
use App\Enums\VideoStatus;
use App\Models\Comment;
use App\Models\Report;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    private Video $video;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->video = Video::factory()->create([
            'status' => VideoStatus::PUBLIC,
            'category_id' => null,
            'language' => null,
            'publication_date' => now(),
            'user_id' => $this->user->id,
            'allow_comments' => true,
            'show_likes' => true,
        ]);
    }

    public function test_report_without_reason(): void
    {
        $response = $this->actingAs($this->user)->withHeaders([
            'Accept' => 'application/json'
        ])->post(route('report'));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrorFor('reason');
    }

    public function test_report_with_bad_type(): void
    {
        $response = $this->actingAs($this->user)->withHeaders([
            'Accept' => 'application/json'
        ])->post(route('report'), [
            'reason' => ReportReason::ABUSIVE->value,
            'type' => 'Bad type'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrorFor('type');
    }

    public function test_report_with_non_existing_record(): void
    {
        $response = $this->actingAs($this->user)->withHeaders([
            'Accept' => 'application/json'
        ])->post(route('report'), [
            'reason' => ReportReason::ABUSIVE->value,
            'type' => Video::class,
            'id' => 999
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrorFor('id');
    }

    public function test_report_with_own_video(): void
    {
        $video = Video::factory()->create([
            'status' => VideoStatus::PUBLIC,
            'publication_date' => now(),
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->withHeaders([
            'Accept' => 'application/json'
        ])->post(route('report'), [
            'reason' => ReportReason::ABUSIVE->value,
            'type' => Video::class,
            'id' => $video->id
        ]);

        // Policy

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_report_with_own_comment(): void
    {
        $video = Video::factory()->create([
            'status' => VideoStatus::PUBLIC,
            'publication_date' => now(),
            'user_id' => $this->user->id,
        ]);

        $comment = Comment::factory()->createQuietly([
            'user_id' => $this->user->id,
            'video_id' => $video->id
        ]);

        $response = $this->actingAs($this->user)->withHeaders([
            'Accept' => 'application/json'
        ])->post(route('report'), [
            'reason' => ReportReason::ABUSIVE->value,
            'type' => Comment::class,
            'id' => $comment->id
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_report_with_own_user(): void
    {
        $response = $this->actingAs($this->user)->withHeaders([
            'Accept' => 'application/json'
        ])->post(route('report'), [
            'reason' => ReportReason::ABUSIVE->value,
            'type' => User::class,
            'id' => $this->user->id
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_report_with_private_video(): void
    {
        $video = Video::factory()->create([
            'status' => VideoStatus::PRIVATE,
            'publication_date' => now(),
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->actingAs($this->user)->withHeaders([
            'Accept' => 'application/json'
        ])->post(route('report'), [
            'reason' => ReportReason::ABUSIVE->value,
            'type' => Video::class,
            'id' => $video->id
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_report_with_planned_video(): void
    {
        $video = Video::factory()->create([
            'status' => VideoStatus::PLANNED,
            'scheduled_date' => now()->addMinutes(30),
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->actingAs($this->user)->withHeaders([
            'Accept' => 'application/json'
        ])->post(route('report'), [
            'reason' => ReportReason::ABUSIVE->value,
            'type' => Video::class,
            'id' => $video->id
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_report_with_banned_comment(): void
    {
        $video = Video::factory()->create([
            'status' => VideoStatus::PUBLIC,
            'publication_date' => now(),
            'user_id' => User::factory()->create()->id,
        ]);

        $comment = Comment::factory()->banned()->createQuietly([
            'user_id' => User::factory()->create()->id,
            'video_id' => $video->id
        ]);

        $response = $this->actingAs($this->user)->withHeaders([
            'Accept' => 'application/json'
        ])->post(route('report'), [
            'reason' => ReportReason::ABUSIVE->value,
            'type' => Comment::class,
            'id' => $comment->id
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_report_with_banned_user(): void
    {
        $user = User::factory()->banned()->create();

        $response = $this->actingAs($this->user)->withHeaders([
            'Accept' => 'application/json'
        ])->post(route('report'), [
            'reason' => ReportReason::ABUSIVE->value,
            'type' => User::class,
            'id' => $user->id
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_report_with_already_report_video(): void
    {
        $video = Video::factory()->create([
            'status' => VideoStatus::PUBLIC,
            'publication_date' => now(),
            'user_id' => User::factory()->create()->id,
        ]);

        Report::factory()->create([
            'user_id' => $this->user->id,
            'reportable_type' => Video::class,
            'reportable_id' => $video->id
        ]);

        $response = $this->actingAs($this->user)->withHeaders([
            'Accept' => 'application/json'
        ])->post(route('report'), [
            'reason' => ReportReason::ABUSIVE->value,
            'type' => Video::class,
            'id' => $video->id
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_report_success(): void
    {
        $video = Video::factory()->create([
            'status' => VideoStatus::PUBLIC,
            'publication_date' => now(),
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->actingAs($this->user)->withHeaders([
            'Accept' => 'application/json'
        ])->post(route('report'), [
            'reason' => ReportReason::ABUSIVE->value,
            'type' => Video::class,
            'id' => $video->id
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $report = Report::all()->first();

        $this->assertEquals(
            [
                $report->user->is($this->user),
                $report->reportable->is($video),
                $report->reason->name === ReportReason::ABUSIVE->name,
            ],
            [true, true, true],
        );
    }
}
