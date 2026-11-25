<?php

namespace Tests\Feature\Api;

use App\Enums\VideoStatus;
use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    private Video $video;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->video = Video::factory()
            ->withStatus(VideoStatus::PUBLIC)
            ->for($this->user)
            ->create([
                'allow_comments' => true
            ]);
    }

    #[Test]
    public function comments_list(): void
    {
        Comment::factory()->forUser()->for($this->video)->count(3)->createQuietly();

        $response = $this->get(route('comments.index', $this->video));

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->has('data', 3, fn (AssertableJson $json) =>
                        $json
                            ->whereType('id', 'integer')
                            ->whereType('class', 'string')
                            ->whereType('content', 'string')
                            ->whereType('parsed_content', 'string')
                            ->whereType('short_content', 'string')
                            ->whereType('is_long', 'boolean')
                            ->has('user', fn (AssertableJson $json) =>
                                $json->whereType('id', 'integer')
                                    ->whereType('username', 'string')
                                    ->whereType('avatar', 'string')
                                    ->whereType('route', 'string')
                                    ->whereType('is_video_author', 'boolean')
                            )
                            ->whereType('video_uuid', 'string')
                            ->whereType('created_at', 'string')
                            ->whereType('is_updated', 'boolean')
                            ->whereType('likes_count', 'integer')
                            ->whereType('dislikes_count', 'integer')
                            ->whereType('is_reply', 'boolean')
                            ->whereType('is_pinned', 'boolean')
                            ->whereType('has_replies', 'boolean')
                            ->whereType('replies_count', 'integer')
                            ->whereType('is_video_author_reply', 'boolean')
                            ->whereType('is_video_author_like', 'boolean')
                            ->etc()
                    )
                    ->has('links')
                    ->has('meta')
                    ->has('count')
            );
    }

    #[Test]
    public function comment_store(): void
    {
        $this
            ->actingAs($this->user)
            ->postJson(
                route('comments.store', $this->video),
                [
                    'content' => 'Test content',
                ]
            )
            ->assertStatus(201);

        $this->assertDatabaseCount('comments', 1);
    }

    #[Test]
    public function comment_update(): void
    {
        $comment = Comment::factory()->for($this->video)->for($this->user)->createQuietly();

        $this
            ->actingAs($this->user)
            ->putJson(
                route('comments.update', ['comment' => $comment, 'video' => $this->video]),
                [
                    'content' => 'New content',
                ]
            )
            ->assertStatus(200);

        $this->assertEquals('New content', $comment->fresh()->content);
    }

    #[Test]
    public function comment_delete(): void
    {
        $comment = Comment::factory()->for($this->video)->for($this->user)->createQuietly();

        $this
            ->actingAs($this->user)
            ->delete(route('comments.destroy', ['comment' => $comment, 'video' => $this->video]))
            ->assertStatus(204);

        $this->isSoftDeletableModel($comment);
    }
}
