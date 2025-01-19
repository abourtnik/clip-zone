<?php

namespace Feature;

use App\Notifications\Account\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_view() :void
    {
        $this
            ->get(route('registration'))
            ->assertViewIs('auth.register');
    }

    public function test_register_success() :void
    {
        Notification::fake();

        $this
            ->post(route('registration.perform'), [
                'username' => 'test',
                'email' => 'test@test.fr',
                'password' => 'aaaaaaaa',
                'password_confirmation' => 'aaaaaaaa',
                'cgu' => true,
            ])
            ->assertRedirectToRoute('login')
            ->assertSessionHas('success');;

        $this->assertDatabaseCount('users', 1);

        $this->assertDatabaseHas('users', [
            'email' =>'test@test.fr',
        ]);

        Notification::assertSentTimes(VerifyEmail::class, 1);
    }
}
