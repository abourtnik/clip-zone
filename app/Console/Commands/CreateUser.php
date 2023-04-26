<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create {count=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create fake users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() : int
    {
        $count = $this->argument('count');

        User::factory($count)->create([
            'password' => Str::random(),
            'avatar' => null,
            'show_subscribers' => true,
            'country' => null,
            'description' => null
        ]);

        return Command::SUCCESS;
    }
}
