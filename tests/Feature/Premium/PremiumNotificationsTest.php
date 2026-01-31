<?php

namespace Tests\Feature\Premium;

use App\Console\Commands\Premium\SendCardExpiration;
use App\Console\Commands\Premium\SendTrialsEnd;
use App\Models\User;
use App\Notifications\Premium\CardExpired;
use App\Notifications\Premium\TrialEnd;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Subscription;
use Stripe\Subscription as StripeSubscription;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
class PremiumNotificationsTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->stripeUser()->create();

        Notification::fake();
    }

    public function test_send_card_expiration_notification(): void
    {
        Subscription::factory()
            ->for($this->user)
            ->count(2)
            ->sequence(
                ['card_expired_at' => now()->addDay()],
                ['card_expired_at' => now()->subDay()],
            )
            ->create();

        Artisan::call(SendCardExpiration::class);

        Notification::assertSentTo([$this->user], CardExpired::class);

        Notification::assertSentTimes(CardExpired::class, 1);
    }

    public function test_send_trials_end_notification(): void
    {
        Subscription::factory()
            ->for($this->user)
            ->count(3)
            ->sequence(
                ['ends_at' => now()],
                ['ends_at' => null, 'trial_ends_at' => now(), 'stripe_status' => StripeSubscription::STATUS_TRIALING],
                ['ends_at' => null, 'trial_ends_at' => now()->addDays(config('plans.trial_period.email_reminder')), 'stripe_status' => StripeSubscription::STATUS_TRIALING],
            )
            ->create();

        Artisan::call(SendTrialsEnd::class);

        Notification::assertSentTo([$this->user], TrialEnd::class);

        Notification::assertSentTimes(TrialEnd::class, 1);
    }
}
