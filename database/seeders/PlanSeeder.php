<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() : void
    {
        $plans = [
            ['name' => 'monthly', 'price' => 500, 'duration' => 1],
            ['name' => 'annually', 'price' => 5000, 'duration' => 7]
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
