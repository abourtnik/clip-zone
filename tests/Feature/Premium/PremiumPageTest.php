<?php

namespace Tests\Feature\Premium;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Plan;
use Laravel\Cashier\SubscriptionBuilder;
use Mockery\MockInterface;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Mockery;
class PremiumPageTest extends TestCase
{
    use RefreshDatabase;

    public User $user;
    public Plan $plan;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->stripeUser()->create();
        $this->plan = Plan::factory()->create();

        Notification::fake();
    }

    public function test_premium_page_view(): void
    {
        $this
            ->get(route('pages.premium'))
            ->assertOk();
    }

    public function test_premium_subscribe(): void
    {
        $user = Mockery::mock($this->user)->makePartial();

        $user->shouldReceive('newSubscription')
            ->once()
            ->with($this->plan->id, $this->plan->stripe_id)
            ->andReturn(
                Mockery::mock(SubscriptionBuilder::class, function (MockInterface $mock) {
                    $mock
                        ->shouldReceive('checkout')
                        ->once()
                        ->andReturn(redirect('https://checkout.stripe.com'));
                })
            );

        $this
            ->actingAs($user)
            ->get(route('premium.subscribe', $this->plan))
            ->assertRedirect('https://checkout.stripe.com');
    }

}
