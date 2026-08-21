<?php

namespace Tests\Feature\Api;

use App\Enums\ThumbnailStatus;
use App\Enums\VideoStatus;
use App\Jobs\ProcessVideo;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VideoUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Queue::fake();
    }

    private function chunkPayload(array $overrides = []): array
    {
        return array_merge([
            'resumableFilename' => 'my-video.mp4',
            'resumableTotalSize' => 1_000_000,
            'file' => UploadedFile::fake()->create('my-video.mp4', 500, 'video/mp4'),
            'resumableIdentifier' => 'abc123identifier',
            'resumableChunkNumber' => 1,
            'resumableTotalChunks' => 1,
        ], $overrides);
    }

    public function test_last_chunk_upload_creates_video_with_thumbnails_and_dispatches_processing(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('videos.upload'), $this->chunkPayload());

        $response->assertOk();

        $this->assertDatabaseCount('videos', 1);

        $video = Video::first();
        $this->assertSame(VideoStatus::DRAFT, $video->status);
        $this->assertSame($user->id, $video->user_id);
        $this->assertSame('my-video.mp4', $video->original_file_name);

        $this->assertCount(3, $video->thumbnails);
        $this->assertTrue($video->thumbnails->every(fn ($thumbnail) => $thumbnail->status === ThumbnailStatus::PENDING));

        $response->assertJson(['route' => route('user.videos.create', $video)]);

        Queue::assertPushed(ProcessVideo::class, fn ($job) => $job->video->is($video));
    }

    public function test_intermediate_chunk_upload_returns_progress_without_creating_video(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('videos.upload'), $this->chunkPayload([
            'resumableChunkNumber' => 2,
            'resumableTotalChunks' => 4,
        ]));

        $response->assertOk()->assertJson(['done' => 50]);

        $this->assertDatabaseCount('videos', 0);
        Queue::assertNothingPushed();
    }

    public function test_guest_cannot_upload(): void
    {
        $response = $this->post(route('videos.upload'), $this->chunkPayload());

        $response->assertRedirect(route('login'));
    }

    public function test_upload_is_rejected_for_unsupported_mimetype(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('videos.upload'), $this->chunkPayload([
            'file' => UploadedFile::fake()->create('my-video.mp4', 500, 'image/png'),
        ]));

        $response->assertInvalid(['file']);
    }

    public function test_upload_is_rejected_when_chunk_exceeds_max_chunk_size(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('videos.upload'), $this->chunkPayload([
            'file' => UploadedFile::fake()->create('my-video.mp4', 10_241, 'video/mp4'),
        ]));

        $response->assertInvalid(['file']);
    }

    public function test_upload_is_rejected_when_total_size_exceeds_plan_limit(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('videos.upload'), $this->chunkPayload([
            'resumableTotalSize' => config('plans.free.max_file_size') + 1,
        ]));

        $response->assertInvalid(['resumableTotalSize']);
    }

    public function test_upload_is_forbidden_when_free_plan_upload_quota_is_reached(): void
    {
        $user = User::factory()->create();

        Video::factory()
            ->count(config('plans.free.max_uploads'))
            ->withStatus(VideoStatus::PUBLIC)
            ->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('videos.upload'), $this->chunkPayload());

        $response->assertForbidden();
        $this->assertDatabaseCount('videos', config('plans.free.max_uploads'));
    }

    public function test_upload_is_forbidden_when_free_plan_storage_quota_is_exceeded(): void
    {
        $user = User::factory()->create();

        $sizePerVideo = intdiv(config('plans.free.max_videos_storage'), 3) + 1;

        Video::factory()
            ->count(3)
            ->withStatus(VideoStatus::PUBLIC)
            ->create([
                'user_id' => $user->id,
                'size' => $sizePerVideo,
            ]);

        $response = $this->actingAs($user)->post(route('videos.upload'), $this->chunkPayload([
            'resumableTotalSize' => 1,
        ]));

        $response->assertForbidden();
    }
}
