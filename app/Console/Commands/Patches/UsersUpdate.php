<?php

namespace App\Console\Commands\Patches;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;

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
    protected $description = 'Update users table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        $users = User::query()
            ->whereNull('slug')
            ->get();

        foreach ($users as $user) {

            $slug = User::generateSlug($user->username);

            try {
                $user->update([
                    'slug' => $slug,
                ]);
            } catch (QueryException $exception) {
                $user->update([
                    'slug' => $slug . '-01',
                ]);
            }
        }

        return Command::SUCCESS;
    }
}
