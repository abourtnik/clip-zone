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
        $date = fake()->dateTimeBetween('-20 years');

        $username = fake()->unique()->userName();

        return [
            'username' => $username,
            'slug' => User::generateSlug($username),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 'password',
            'last_login_at' => now(),
            'last_login_ip' => fake()->ipv4(),
            'remember_token' => Str::random(10),
            'avatar' => null,
            'description' => fake()->realText(config('validation.user.description.max')),
            'country' => fake()->countryCode(),
            'website' => fake()->domainName(),
            'show_subscribers' => fake()->boolean(90),
            'created_at' => $date,
            'updated_at' => $date
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return Factory
     */
    public function unverified() : Factory
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the comment is banned.
     *
     * @return Factory
     */
    public function banned() : Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'banned_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the user is admin.
     *
     * @return Factory
     */
    public function admin() : Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_admin' => true,
            ];
        });
    }
}
