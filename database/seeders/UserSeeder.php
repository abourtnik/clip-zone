<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() : void
    {
        // Admin
        User::factory()
            ->admin()
            ->withDefaultPlaylist()
            ->create([
                'username' => 'admin',
                'password' => 'admin'
            ]);

        // Basic users
        User::factory(4)
            ->withDefaultPlaylist()
            ->create();

    }
}
