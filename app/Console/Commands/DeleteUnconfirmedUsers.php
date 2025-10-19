<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class DeleteUnconfirmedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete-unconfirmed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove unconfirmed users after their are expired';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        $count = User::query()
            ->whereNull('email_verified_at')
            ->where('created_at', '<', now()->subMinutes(Config::get('auth.verification.expire', 1440)))
            ->forceDelete();

        $this->info(now()->format('Y-m-d H:i:s').' - Delete ' .$count. ' unconfirmed users');

        return Command::SUCCESS;
    }
}
