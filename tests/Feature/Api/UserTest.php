<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_show(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('users.show', $user->id));

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json
                    ->whereType('id', 'integer')
                    ->whereType('username', 'string')
                    ->whereType('slug', 'string')
                    ->whereType('avatar', 'string')
                    ->whereType('banner', 'string')
                    ->whereType('show_subscribers', 'boolean')
                    ->when($user->show_subscribers, fn() =>
                        $json->whereType('subscribers', 'integer')
                    )
                    ->whereType('videos_count', 'integer')
                    ->whereType('short_description', 'string')
                    ->whereType('description', 'string')
                    ->whereType('website', 'string')
                    ->whereType('country_code', 'string')
                    ->whereType('country', 'string')
                    ->whereType('views', 'integer')
                    ->whereType('created_at', 'string')
                    ->whereType('pinned_video', 'array|null')
                    ->whereType('videos', 'array')
                    ->whereType('playlists', 'array')
            );
    }
}
