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
            [
                'name' => 'daily',
                'price' => 5.00,
                'duration' => 1,
            ],
            [
                'name' => 'weekly',
                'price' => 50.00,
                'duration' => 7,
            ]
        ];

        foreach ($plans as $plan) {
            Plan::create([
                'name' => $plan['name'],
                'price' => $plan['price'],
                'duration' => $plan['duration'],
            ]);
        }
    }
}
