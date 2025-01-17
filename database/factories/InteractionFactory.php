<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;
use App\Models\Interaction;

/**
 * @extends Factory<Interaction>
 */
class InteractionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'status' => fake()->boolean(),
            'perform_at' => fake()->dateTimeBetween('-1 year'),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure() : static
    {
        return $this->sequence(
            fn ($sequence) => ['user_id' => fake()->unique($sequence->index === 0)->randomElement(User::pluck('id'))]
        );
    }
}
