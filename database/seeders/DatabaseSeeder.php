<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();

         User::factory()->create([
             'username' => 'anton',
             'email' => 'anton.bourtnik@hotmail.fr',
             'email_verified_at' => now(),
             'password' => Hash::make('0000'),
             'remember_token' => Str::random(10),
             'is_admin' => true
         ]);

        Article::factory(100000)->create();
    }
}
