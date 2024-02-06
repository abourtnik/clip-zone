<?php

namespace App\Jobs\Stripe;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\Premium\Welcome;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Laravel\Cashier\Cashier;
use Stripe\Subscription as StripeSubscription;
use Throwable;

class SubscriptionCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public int $timeout = 120;

    public array $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : void
    {
        if ($this->data['status'] === StripeSubscription::STATUS_INCOMPLETE) {
            return;
        }

        $user = User::where('stripe_id', $this->data['customer'])->firstOrFail();

        $plan = Plan::where('stripe_id', $this->data['plan']['id'])->firstOrFail();

        $default_payment_method = Cashier::stripe()->subscriptions->retrieve($this->data['id'])->default_payment_method;

        $paymentMethod = Cashier::stripe()->paymentMethods->retrieve($default_payment_method);

        // User Cancel Subscription and Renew
        if ($user->premium_subscription) {
            $user->premium_subscription()->update([
                'next_payment' => Carbon::createFromTimestamp($this->data['current_period_end']),
                'stripe_status' => $this->data['status'],
                'plan_id' => $plan->id,
                'stripe_id' => $this->data['id'],
                'ends_at' => null,
                'card_last4' => $paymentMethod->card->last4,
                'card_expired_at' => Carbon::createFromDate($paymentMethod->card->exp_year, $paymentMethod->card->exp_month)->endOfMonth()
            ]);
        }

        else {
            Subscription::create([
                'next_payment' => Carbon::createFromTimestamp($this->data['current_period_end']),
                'stripe_status' => $this->data['status'],
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'stripe_id' => $this->data['id'],
                'trial_ends_at' => Carbon::createFromTimestamp($this->data['trial_end']),
                'card_last4' => $paymentMethod->card->last4,
                'card_expired_at' => Carbon::createFromDate($paymentMethod->card->exp_year, $paymentMethod->card->exp_month)->endOfMonth()
            ]);
        }

        $user->notify(new Welcome());
    }

    /**
     * Handle a job failure.
     *
     * @param Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception) : void
    {

    }
}
