<?php

namespace App\Jobs\Stripe;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SubscriptionUpdated implements ShouldQueue
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
        $subscription = Subscription::where('stripe_id', $this->data['id'])->firstOrFail();

        $subscription->update([
            'stripe_status' => $this->data['status']
        ]);

        if ($this->data['cancel_at_period_end']) {
            $subscription->update([
                'ends_at' => $subscription->on_trial
                    ? $subscription->trial_ends_at
                    : Carbon::createFromTimestamp($this->data['current_period_end'])
            ]);
        } else {
            $subscription->update([
                'next_payment' => Carbon::createFromTimestamp($this->data['current_period_end']),
                'ends_at' => null
            ]);
        }
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
