<?php

namespace Tests\Feature\Premium;

use App\Data\StripeCard;
use App\Models\Plan;
use App\Models\User;
use App\Notifications\Premium\Invoice;
use App\Notifications\Premium\Welcome;
use App\Services\StripeService;
use Tests\Fixtures\StripeApiFixtures;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Cashier\Http\Middleware\VerifyWebhookSignature;
use Stripe\Subscription as StripeSubscription;
use App\Models\Subscription;
use Mockery\MockInterface;
use Tests\TestCase;

class StripeWebhookTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public Plan $plan;

    public string $subscriptionStripeId;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()
            ->stripeUser()
            ->create();

        $this->plan = Plan::factory()->create();

        $this->subscriptionStripeId = fake()->bothify('sub_#???????????????????????');

        $this->mock(StripeService::class, function (MockInterface $mock) {
            $mock
                ->shouldReceive('getUserCard')
                ->with($this->user->stripe_id)
                ->andReturn(new StripeCard('visa', '4242', 12, 2027));
            $mock
                ->shouldReceive('getTransactionFee')
                ->andReturn(33);
        });

        Notification::fake();
    }

    public function test_customer_subscription_created(): void
    {
        $now = now();

        $this->withoutMiddleware(VerifyWebhookSignature::class)
            ->postJson('stripe-webhook', StripeApiFixtures::customerSubscriptionCreated($this->subscriptionStripeId, $this->user, $this->plan, $now))
            ->assertNoContent();

        $this->assertDatabaseHas('premiums', [
            'stripe_status' => StripeSubscription::STATUS_ACTIVE,
            'next_payment' => $now->toDateTimeString(),
            'user_id' => $this->user->id,
            'plan_id' => $this->plan->id,
            'stripe_id' => $this->subscriptionStripeId,
            'trial_ends_at' => $now->copy()->addMonth()->toDateTimeString(),
            'card_last4' => '4242',
            'card_expired_at' => '2027-12-31',
            'ends_at' => null
        ]);

        Notification::assertSentTo([$this->user], Welcome::class);
    }

    public function test_customer_subscription_update_to_next_payment(): void
    {
        $subscription = Subscription::factory()
            ->for($this->user)
            ->for($this->plan)
            ->create();

        $nextPayment = $subscription->next_payment->addMonth();

        $this->withoutMiddleware(VerifyWebhookSignature::class)
            ->postJson('stripe-webhook', StripeApiFixtures::customerSubscriptionUpdated($subscription, $nextPayment, false))
            ->assertNoContent();

        $subscription->refresh();

        $this->assertEquals($subscription->next_payment, $nextPayment);
    }

    public function test_customer_cancel_subscription_before_trial_end(): void
    {
        $subscription = Subscription::factory()
            ->for($this->user)
            ->for($this->plan)
            ->create([
                'trial_ends_at' => now()->addDay()
            ]);

        $cancelDate = now();

        $this->withoutMiddleware(VerifyWebhookSignature::class)
            ->postJson('stripe-webhook', StripeApiFixtures::customerSubscriptionUpdated($subscription, $cancelDate))
            ->assertNoContent();

        $subscription->refresh();

        $this->assertEquals($subscription->ends_at, $subscription->trial_ends_at);
    }

    public function test_customer_cancel_subscription_after_trial_end(): void
    {
        $subscription = Subscription::factory()
            ->for($this->user)
            ->for($this->plan)
            ->create([
                'trial_ends_at' => now()->subDay()
            ]);

        $cancelDate = now();

        $this->withoutMiddleware(VerifyWebhookSignature::class)
            ->postJson('stripe-webhook', StripeApiFixtures::customerSubscriptionUpdated($subscription, $cancelDate))
            ->assertNoContent();

        $subscription->refresh();

        $this->assertEquals($subscription->ends_at->timestamp, $cancelDate->timestamp);
    }

    public function test_invoice_payment_succeeded(): void
    {
        $subscription = Subscription::factory()
            ->for($this->user)
            ->for($this->plan)
            ->create();

        $this->withoutMiddleware(VerifyWebhookSignature::class)
            ->postJson('stripe-webhook', StripeApiFixtures::invoicePaymentSucceeded($subscription))
            ->assertNoContent();

        $this->assertDatabaseCount('transactions', 1);

        Notification::assertSentTo([$this->user], Invoice::class);
    }
}
