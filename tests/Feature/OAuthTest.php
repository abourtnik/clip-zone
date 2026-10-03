<?php

namespace Tests\Feature;

use Laravel\Socialite\Socialite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Laravel\Socialite\Two\User as SocialiteUser;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class OAuthTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Socialite::fake('google', SocialiteUser::fake([
            'id' => 'google-123',
            'nickname' => 'john.doe',
            'name' => 'John Doe',
            'email' => 'john.doe@google.com',
            'avatar' => 'https://example.com/avatar.jpg'
        ]));

        Storage::fake('local');

        Http::preventStrayRequests();

        Http::fake([
            'https://example.com/avatar.jpg' => Http::response('fake-image-content', 200),
        ]);
    }

    #[Test]
    public function redirectProvider() :void
    {
        $this
            ->get(route('oauth.connect', ['service' => 'google']))
            ->assertRedirect();
    }

    #[Test]
    public function callbackProviderGuest() :void
    {
        $this
            ->get(route('oauth.callback', ['service' => 'google']))
            ->assertRedirectToRoute('user.index');

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'username' => 'john.doe',
            'email' => 'john.doe@google.com',
            'google_id' => 'google-123',
        ]);

        Storage::disk('local')
            ->assertCount(User::AVATAR_FOLDER, 1);
    }

    #[Test]
    public function callbackProviderConnected() :void
    {
        $this
            ->actingAs($this->user)
            ->get(route('oauth.callback', ['service' => 'google']))
            ->assertRedirectToRoute('user.edit');

        $this->assertEquals('google-123', $this->user->google_id);
    }

    #[Test]
    public function unlink() :void
    {
        $user = User::factory()->create([
            'google_id' => 'google-123'
        ]);

        $this
            ->actingAs($user)
            ->delete(route('oauth.unlink', ['service' => 'google']))
            ->assertRedirectToRoute('user.edit');

        $this->assertEquals(null, $this->user->google_id);
    }
}
