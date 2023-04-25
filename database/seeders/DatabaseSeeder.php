<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    //use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() : void
    {
        $this->call([
            CategorySeeder::class,
        ]);

        if (app()->isProduction()) {
            $this->call([
                ProductionSeeder::class,
            ]);
        } else {
            $this->call([
                LocalSeeder::class,
            ]);
        }
    }
}
