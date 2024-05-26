<?php

namespace Tests\Feature;

use App\Enums\ImageType;
use App\Enums\VideoStatus;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VideoTest extends TestCase
{
    use RefreshDatabase;

    private Video $video;
    private User $user;

    public function setUp() : void
    {
        parent::setUp();

        Storage::fake('thumbnails');

        $this->user = User::factory()->create();

        $this->video = Video::factory()->create([
            'status' => VideoStatus::DRAFT,
            'category_id' => null,
            'language' => null,
            'publication_date' => null,
            'user_id' => $this->user->id,
            'allow_comments' => true,
            'show_likes' => true,
        ]);

        $this->created_video = Video::factory()->create([
            'status' => VideoStatus::PUBLIC,
            'category_id' => null,
            'language' => null,
            'publication_date' => null,
            'user_id' => $this->user->id,
            'allow_comments' => true,
            'show_likes' => true,
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_created_public_video() :void
    {
        $response = $this->actingAs($this->user)->post(route('user.videos.store', $this->video), [
            ...$this->video->toArray(),
            ...[
                'status' => VideoStatus::PUBLIC->value,
                'thumbnail' => 1,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::all()->first();

        $this->assertEquals(VideoStatus::PUBLIC, $updated->status);
        $this->assertNotNull($updated->publication_date);
        $this->assertNull($updated->scheduled_date);
    }

    public function test_created_private_video() :void
    {
        $response = $this->actingAs($this->user)->post(route('user.videos.store', $this->video), [
            ...$this->video->toArray(),
            ...[
                'status' => VideoStatus::PRIVATE->value,
                'thumbnail' => 1,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::all()->first();

        $this->assertEquals(VideoStatus::PRIVATE, $updated->status);
        $this->assertNull($updated->publication_date);
        $this->assertNull($updated->scheduled_date);
    }

    public function test_created_protected_video() :void
    {
        $response = $this->actingAs($this->user)->post(route('user.videos.store', $this->video), [
            ...$this->video->toArray(),
            ...[
                'status' => VideoStatus::UNLISTED->value,
                'thumbnail' => 1,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::all()->first();

        $this->assertEquals(VideoStatus::UNLISTED, $updated->status);
        $this->assertNull($updated->publication_date);
        $this->assertNull($updated->scheduled_date);
    }

    public function test_created_planned_video() :void
    {
        $response = $this->actingAs($this->user)->post(route('user.videos.store', $this->video), [
            ...$this->video->toArray(),
            ...[
                'status' => VideoStatus::PLANNED->value,
                'scheduled_date' => Carbon::now()->addMinutes(30),
                'thumbnail' => 1,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::all()->first();

        $this->assertEquals(VideoStatus::PLANNED, $updated->status);
        $this->assertNotNull($updated->publication_date);
        $this->assertNotNull($updated->scheduled_date);
        $this->assertEquals($updated->publication_date, $updated->scheduled_date);
    }

    public function test_updated_public_video_to_public_video() :void
    {
        $date = Carbon::now()->subMinute(1);

        $video = Video::factory()->create([
            'status' => VideoStatus::PUBLIC,
            'publication_date' => $date,
            'user_id' => $this->user->id,
            'thumbnail' => 1
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PUBLIC->value,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PUBLIC, $updated->status);
        $this->assertTrue($date->is($updated->publication_date));
        $this->assertNull($updated->scheduled_date);
    }

    public function test_updated_public_video_to_private_video() :void
    {
        $date = Carbon::now()->subMinute(1);

        $video = Video::factory()->create([
            'status' => VideoStatus::PUBLIC,
            'publication_date' => $date,
            'user_id' => $this->user->id,
            'thumbnail' => 1
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PRIVATE->value,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PRIVATE, $updated->status);
        $this->assertTrue($date->is($updated->publication_date));
        $this->assertNull($updated->scheduled_date);
    }

    public function test_updated_public_video_to_unlisted_video() :void
    {
        $date = Carbon::now()->subMinute(1);

        $video = Video::factory()->create([
            'status' => VideoStatus::PUBLIC,
            'publication_date' => $date,
            'user_id' => $this->user->id,
            'thumbnail' => 1
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::UNLISTED->value,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::UNLISTED, $updated->status);
        $this->assertTrue($date->is($updated->publication_date));
        $this->assertNull($updated->scheduled_date);
    }

    public function test_updated_public_video_to_planned_video() :void
    {
        $date = Carbon::now()->subMinute(1);
        $scheduled_date = Carbon::now()->addMinutes(30);

        $video = Video::factory()->create([
            'status' => VideoStatus::PUBLIC,
            'publication_date' => $date,
            'user_id' => $this->user->id,
            'thumbnail' => 1
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PLANNED->value,
                'scheduled_date' => $scheduled_date
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PLANNED, $updated->status);
        $this->assertTrue($date->is($updated->publication_date));
        $this->assertTrue($scheduled_date->is($updated->scheduled_date));
    }

    public function test_updated_private_video_to_public_video() :void
    {
        $video = Video::factory()->create([
            'status' => VideoStatus::PRIVATE,
            'publication_date' => null,
            'user_id' => $this->user->id,
            'thumbnail' => 1
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PUBLIC->value,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PUBLIC, $updated->status);
        $this->assertNotNull($updated->publication_date);
        $this->assertNull($updated->scheduled_date);
    }

    public function test_updated_private_video_to_private_video() :void
    {
        $video = Video::factory()->create([
            'status' => VideoStatus::PRIVATE,
            'publication_date' => null,
            'user_id' => $this->user->id,
            'thumbnail' => 1
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PRIVATE->value,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PRIVATE, $updated->status);
        $this->assertNull($updated->publication_date);
        $this->assertNull($updated->scheduled_date);
    }

    public function test_updated_private_video_to_unlisted_video() :void
    {
        $video = Video::factory()->create([
            'status' => VideoStatus::PRIVATE,
            'publication_date' => null,
            'user_id' => $this->user->id,
            'thumbnail' => 1
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::UNLISTED->value,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::UNLISTED, $updated->status);
        $this->assertNull($updated->publication_date);
        $this->assertNull($updated->scheduled_date);
    }

    public function test_updated_private_video_to_planned_video() :void
    {
        $scheduled_date = Carbon::now()->addMinutes(30);

        $video = Video::factory()->create([
            'status' => VideoStatus::PRIVATE,
            'publication_date' => null,
            'user_id' => $this->user->id,
            'thumbnail' => 1
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PLANNED->value,
                'scheduled_date' => $scheduled_date,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PLANNED, $updated->status);
        $this->assertTrue($updated->publication_date->is($scheduled_date));
        $this->assertTrue($updated->scheduled_date->is($scheduled_date));
    }

    public function test_updated_planned_video_to_public_video() :void
    {
        $scheduled_date = Carbon::now()->addMinutes(30);

        $video = Video::factory()->create([
            'status' => VideoStatus::PLANNED,
            'publication_date' => $scheduled_date,
            'scheduled_date' => $scheduled_date,
            'user_id' => $this->user->id,
            'thumbnail' => 1
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PUBLIC->value,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PUBLIC, $updated->status);
        $this->assertFalse($scheduled_date->is($updated->publication_date));
        $this->assertNull($updated->scheduled_date);
    }

    public function test_updated_planned_video_to_private_video() :void
    {
        $scheduled_date = Carbon::now()->addMinutes(30);

        $video = Video::factory()->create([
            'status' => VideoStatus::PLANNED,
            'publication_date' => $scheduled_date,
            'scheduled_date' => $scheduled_date,
            'user_id' => $this->user->id,
            'thumbnail' => 1
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PRIVATE->value,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PRIVATE, $updated->status);
        $this->assertNull($updated->publication_date);
        $this->assertNull($updated->scheduled_date);
    }

    public function test_updated_planned_video_to_planned_video() :void
    {
        $scheduled_date = Carbon::now()->addMinutes(30);

        $video = Video::factory()->create([
            'status' => VideoStatus::PLANNED,
            'publication_date' => $scheduled_date,
            'scheduled_date' => $scheduled_date,
            'user_id' => $this->user->id,
            'thumbnail' => 1
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PLANNED->value,
                'scheduled_date' => Carbon::now()->addMinutes(40),
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PLANNED, $updated->status);
        $this->assertTrue($updated->publication_date->is(Carbon::now()->addMinutes(40)));
        $this->assertTrue($updated->scheduled_date->is(Carbon::now()->addMinutes(40)));
    }
}
