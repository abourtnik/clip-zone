<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() : void
    {
        User::factory(10)->create([
            'password' => Str::random(),
            'avatar' => null,
            'show_subscribers' => true,
            'country' => null
        ]);
    }
}
