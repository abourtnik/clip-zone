<?php

namespace Tests\Feature;

use App\Enums\VideoStatus;
use App\Models\Thumbnail;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            'published_at' => null,
            'user_id' => $this->user->id,
            'allow_comments' => true,
            'show_likes' => true,
        ]);

        $this->created_video = Video::factory()->create([
            'status' => VideoStatus::PUBLIC,
            'category_id' => null,
            'language' => null,
            'published_at' => null,
            'user_id' => $this->user->id,
            'allow_comments' => true,
            'show_likes' => true,
        ]);

        $this->thumbnail = Thumbnail::factory()->generated()->create([
            'video_id' => $this->video->id,
            'is_active' => true
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
                'thumbnail' => $this->thumbnail->id,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::all()->first();

        $this->assertEquals(VideoStatus::PUBLIC, $updated->status);
        $this->assertNotNull($updated->published_at);
        $this->assertNull($updated->scheduled_at);
    }

    public function test_created_private_video() :void
    {
        $response = $this->actingAs($this->user)->post(route('user.videos.store', $this->video), [
            ...$this->video->toArray(),
            ...[
                'status' => VideoStatus::PRIVATE->value,
                'thumbnail' => $this->thumbnail->id,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::all()->first();

        $this->assertEquals(VideoStatus::PRIVATE, $updated->status);
        $this->assertNull($updated->published_at);
        $this->assertNull($updated->scheduled_at);
    }

    public function test_created_protected_video() :void
    {
        $response = $this->actingAs($this->user)->post(route('user.videos.store', $this->video), [
            ...$this->video->toArray(),
            ...[
                'status' => VideoStatus::UNLISTED->value,
                'thumbnail' => $this->thumbnail->id,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::all()->first();

        $this->assertEquals(VideoStatus::UNLISTED, $updated->status);
        $this->assertNull($updated->published_at);
        $this->assertNull($updated->scheduled_at);
    }

    public function test_created_planned_video() :void
    {
        $response = $this->actingAs($this->user)->post(route('user.videos.store', $this->video), [
            ...$this->video->toArray(),
            ...[
                'status' => VideoStatus::PLANNED->value,
                'scheduled_at' => Carbon::now()->addMinutes(30),
                'thumbnail' => $this->thumbnail->id,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::all()->first();

        $this->assertEquals(VideoStatus::PLANNED, $updated->status);
        $this->assertNotNull($updated->published_at);
        $this->assertNotNull($updated->scheduled_at);
        $this->assertEquals($updated->published_at, $updated->scheduled_at);
    }

    public function test_updated_public_video_to_public_video() :void
    {
        $date = Carbon::now()->subMinute(1);

        $video = Video::factory()->create([
            'status' => VideoStatus::PUBLIC,
            'published_at' => $date,
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PUBLIC->value,
                'thumbnail' => Thumbnail::factory()->active()->create(['video_id' => $video->id])->id,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PUBLIC, $updated->status);
        $this->assertTrue($date->is($updated->published_at));
        $this->assertNull($updated->scheduled_at);
    }

    public function test_updated_public_video_to_private_video() :void
    {
        $date = Carbon::now()->subMinute(1);

        $video = Video::factory()->create([
            'status' => VideoStatus::PUBLIC,
            'published_at' => $date,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PRIVATE->value,
                'thumbnail' => Thumbnail::factory()->active()->create(['video_id' => $video->id])->id,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PRIVATE, $updated->status);
        $this->assertTrue($date->is($updated->published_at));
        $this->assertNull($updated->scheduled_at);
    }

    public function test_updated_public_video_to_unlisted_video() :void
    {
        $date = Carbon::now()->subMinute(1);

        $video = Video::factory()->create([
            'status' => VideoStatus::PUBLIC,
            'published_at' => $date,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::UNLISTED->value,
                'thumbnail' => Thumbnail::factory()->active()->create(['video_id' => $video->id])->id,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::UNLISTED, $updated->status);
        $this->assertTrue($date->is($updated->published_at));
        $this->assertNull($updated->scheduled_at);
    }

    public function test_updated_public_video_to_planned_video() :void
    {
        $date = Carbon::now()->subMinute(1);
        $scheduled_at = Carbon::now()->addMinutes(30);

        $video = Video::factory()->create([
            'status' => VideoStatus::PUBLIC,
            'published_at' => $date,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PLANNED->value,
                'scheduled_at' => $scheduled_at,
                'thumbnail' => Thumbnail::factory()->active()->create(['video_id' => $video->id])->id,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PLANNED, $updated->status);
        $this->assertTrue($date->is($updated->published_at));
        $this->assertTrue($scheduled_at->is($updated->scheduled_at));
    }

    public function test_updated_private_video_to_public_video() :void
    {
        $video = Video::factory()->create([
            'status' => VideoStatus::PRIVATE,
            'published_at' => null,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PUBLIC->value,
                'thumbnail' => Thumbnail::factory()->active()->create(['video_id' => $video->id])->id,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PUBLIC, $updated->status);
        $this->assertNotNull($updated->published_at);
        $this->assertNull($updated->scheduled_at);
    }

    public function test_updated_private_video_to_private_video() :void
    {
        $video = Video::factory()->create([
            'status' => VideoStatus::PRIVATE,
            'published_at' => null,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PRIVATE->value,
                'thumbnail' => Thumbnail::factory()->active()->create(['video_id' => $video->id])->id,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PRIVATE, $updated->status);
        $this->assertNull($updated->published_at);
        $this->assertNull($updated->scheduled_at);
    }

    public function test_updated_private_video_to_unlisted_video() :void
    {
        $video = Video::factory()->create([
            'status' => VideoStatus::PRIVATE,
            'published_at' => null,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::UNLISTED->value,
                'thumbnail' => Thumbnail::factory()->active()->create(['video_id' => $video->id])->id,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::UNLISTED, $updated->status);
        $this->assertNull($updated->published_at);
        $this->assertNull($updated->scheduled_at);
    }

    public function test_updated_private_video_to_planned_video() :void
    {
        $scheduled_at = Carbon::now()->addMinutes(30);

        $video = Video::factory()->create([
            'status' => VideoStatus::PRIVATE,
            'published_at' => null,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PLANNED->value,
                'scheduled_at' => $scheduled_at,
                'thumbnail' => Thumbnail::factory()->active()->create(['video_id' => $video->id])->id,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PLANNED, $updated->status);
        $this->assertTrue($updated->published_at->is($scheduled_at));
        $this->assertTrue($updated->scheduled_at->is($scheduled_at));
    }

    public function test_updated_planned_video_to_public_video() :void
    {
        $scheduled_at = Carbon::now()->addMinutes(30);

        $video = Video::factory()->create([
            'status' => VideoStatus::PLANNED,
            'published_at' => $scheduled_at,
            'scheduled_at' => $scheduled_at,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PUBLIC->value,
                'thumbnail' => Thumbnail::factory()->active()->create(['video_id' => $video->id])->id,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PUBLIC, $updated->status);
        $this->assertFalse($scheduled_at->is($updated->published_at));
        $this->assertNull($updated->scheduled_at);
    }

    public function test_updated_planned_video_to_private_video() :void
    {
        $scheduled_at = Carbon::now()->addMinutes(30);

        $video = Video::factory()->create([
            'status' => VideoStatus::PLANNED,
            'published_at' => $scheduled_at,
            'scheduled_at' => $scheduled_at,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PRIVATE->value,
                'thumbnail' => Thumbnail::factory()->active()->create(['video_id' => $video->id])->id,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PRIVATE, $updated->status);
        $this->assertNull($updated->published_at);
        $this->assertNull($updated->scheduled_at);
    }

    public function test_updated_planned_video_to_planned_video() :void
    {
        $scheduled_at = Carbon::now()->addMinutes(30);

        $video = Video::factory()->create([
            'status' => VideoStatus::PLANNED,
            'published_at' => $scheduled_at,
            'scheduled_at' => $scheduled_at,
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->put(route('user.videos.update', $video), [
            ...$video->toArray(),
            ...[
                'status' => VideoStatus::PLANNED->value,
                'scheduled_at' => Carbon::now()->addMinutes(40),
                'thumbnail' => Thumbnail::factory()->active()->create(['video_id' => $video->id])->id,
            ]
        ]);

        $response->assertRedirect(route('user.videos.index'));

        $updated = Video::find($video->id);

        $this->assertEquals(VideoStatus::PLANNED, $updated->status);
        $this->assertTrue($updated->published_at->is(Carbon::now()->addMinutes(40)));
        $this->assertTrue($updated->scheduled_at->is(Carbon::now()->addMinutes(40)));
    }
}
