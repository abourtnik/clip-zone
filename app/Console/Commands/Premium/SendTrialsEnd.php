<?php

namespace App\Console\Commands\Premium;

use App\Models\Subscription;
use App\Notifications\Premium\TrialEnd;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendTrialsEnd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'premium:trial_end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to active trial subscriptions which finished in 7 days';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        $subscriptions = Subscription::query()
            ->whereNull('ends_at')
            ->whereDate('trial_ends_at', '>', Carbon::now())
            ->whereDate('trial_ends_at', '=', Carbon::now()->add('days', config('plans.trial_period.email_reminder')))
            ->get();

        foreach ($subscriptions as $subscription) {
            $subscription->user->notify(new TrialEnd());
            $this->info(now()->format('Y-m-d H:i:s',).' - Notify ' .$subscription->user->username. '('.$subscription->user->id.') trials end.');
        }

        return Command::SUCCESS;
    }
}
