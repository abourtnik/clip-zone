<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Notifications\Activity\NewSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;


class SubscribeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_subscribe_ok(): void
    {
        Notification::fake();

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this
            ->actingAs($user1)
            ->post(route('subscribe', $user2))
            ->assertNoContent();

        $this->assertDatabaseHas('subscriptions', [
            'subscriber_id' => $user1->id,
            'user_id' => $user2->id
        ]);

        Notification::assertSentTo(
            [$user2], NewSubscriber::class
        );
    }

    public function test_subscribe_yourself(): void
    {
        $user1 = User::factory()->create();

        $this
            ->actingAs($user1)
            ->post(route('subscribe', $user1))
            ->assertForbidden();
    }

    public function test_subscribe_rate_limit(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        foreach (range(1, 5) as $i) {
            $this->actingAs($user1)->post(route('subscribe', $user2))->assertNoContent();
        }

        $this->actingAs($user1)->postJson(route('subscribe', $user2))->assertTooManyRequests();
    }
}
