<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UsersUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        $users = User::all();

        foreach ($users as $user) {

            $user->update([
                'slug' => User::generateSlug($user->username),
            ]);
        }

        return Command::SUCCESS;
    }
}