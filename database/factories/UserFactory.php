<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'username' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 'password',
            'last_login_at' => now(),
            'last_login_ip' => fake()->ipv4(),
            'remember_token' => Str::random(10),
            'avatar' => fake()->randomElement(array_merge(array_map(fn($i) => 'default-' .$i . '.png', range(1, 10)), [null])),
            'description' => fake()->realText(5000),
            'country' => fake()->countryCode(),
            'website' => fake()->domainName(),
            'show_subscribers' => fake()->boolean(90)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
