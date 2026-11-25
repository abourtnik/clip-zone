<?php

namespace Tests\Feature\Api;

use App\Enums\VideoStatus;
use App\Models\Subtitle;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SubtitleTest extends TestCase
{
    use RefreshDatabase;

    private Video $video;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->video = Video::factory()->withStatus(VideoStatus::PUBLIC)->for($this->user)->create();
    }

    #[Test]
    public function subtitle_list(): void
    {
        Subtitle::factory()->for($this->video)->count(3)->create();

        $response = $this
            ->actingAs($this->user)
            ->getJson(route('subtitles.index', $this->video));

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->has('data', 3, fn (AssertableJson $json) =>
                        $json
                            ->whereType('id', 'integer')
                            ->whereType('video_id', 'string')
                            ->whereType('name', 'string')
                            ->whereType('language', 'string|null')
                            ->whereType('file', 'string|null')
                            ->whereType('file_url', 'string|null')
                            ->whereType('status', 'integer')
                            ->whereType('is_generated', 'boolean')
                            ->whereType('is_uploaded', 'boolean')
                            ->whereType('is_enabled', 'boolean')
                            ->whereType('created_at', 'string')
                            ->etc()
                    )
            );
    }

    #[Test]
    public function subtitle_store(): void
    {
        $this
            ->actingAs($this->user)
            ->postJson(
                route('subtitles.store', $this->video),
                [
                    'name' => 'Test subtitle',
                    'language' => 'fr',
                    'file' => UploadedFile::fake()->create('fr.vtt', 500, 'text/vtt'),
                    'is_enabled' => true,
                ]
            )
            ->assertStatus(201);

        $this->assertDatabaseCount('subtitles', 1);
    }

    #[Test]
    public function subtitle_update(): void
    {
        $subtitle = Subtitle::factory()->for($this->video)->uploaded()->create();

        $this
            ->actingAs($this->user)
            ->putJson(
                route('subtitles.update', ['subtitle' => $subtitle, 'video' => $this->video]),
                [
                    'name' => 'New name',
                    'language' => $subtitle->language,
                    'is_enabled' => true,
                ]
            )
            ->assertStatus(200);

        $this->assertEquals('New name', $subtitle->fresh()->name);
    }

    #[Test]
    public function subtitle_toggle(): void
    {
        $subtitle = Subtitle::factory()->for($this->video)->create(['is_enabled' => true]);

        $this
            ->actingAs($this->user)
            ->patch(route('subtitles.toggle', ['subtitle' => $subtitle, 'video' => $this->video]),)
            ->assertStatus(200);

        $this->assertNotEquals($subtitle->is_enabled, $subtitle->fresh()->is_enabled);
    }

    #[Test]
    public function subtitle_delete(): void
    {
        $subtitle = Subtitle::factory()->for($this->video)->uploaded()->create();

        $this
            ->actingAs($this->user)
            ->delete(route('subtitles.destroy', ['subtitle' => $subtitle, 'video' => $this->video]))
            ->assertStatus(204);

        $this->isSoftDeletableModel($subtitle);
    }
}
