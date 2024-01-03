<?php

namespace App\Console\Commands\Premium;

use App\Models\Subscription;
use App\Notifications\Premium\CardExpired;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendCardExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'premium:card_expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to users which card expire at the end of the month';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        // Card Expires this month
        $subscriptions = Subscription::query()
            ->whereNull('ends_at')
            ->whereRaw('DATE_FORMAT(card_expired_at, "%Y-%m") = "' .Carbon::now()->format('Y-m') .'"')
            ->get();

        foreach ($subscriptions as $subscription) {
            $subscription->user->notify(new CardExpired());
            $this->info(now()->format('Y-m-d H:i:s',).' - Notify ' .$subscription->user->username. '('.$subscription->user->id.') card expiration.');
        }

        return Command::SUCCESS;
    }
}
