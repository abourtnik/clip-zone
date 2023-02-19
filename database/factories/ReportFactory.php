<?php

namespace Database\Factories;

use App\Enums\ReportReason;
use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        $date = fake()->dateTimeBetween('-1 year');
        $user = User::inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'reason' => fake()->randomElement(array_values(ReportReason::get())),
            'comment' => fake()->realText(5000),
            'status' => ReportStatus::PROGRESS->value,
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
