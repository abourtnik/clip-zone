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
    protected $description = 'Remove unconfirmed users after their token are expired';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        User::whereNull('email_verified_at')
            ->where('created_at', '<', now()->subMinutes(Config::get('auth.verification.expire', 60)))
            ->delete();

        return Command::SUCCESS;
    }
}
