<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login_view() :void
    {
        $response = $this->get(route('login'));

        dd($response);

        $response->assertViewIs('auth.login');
    }

    public function test_login_success() :void
    {
        $user = User::factory()->create([
            'email' => 'test@test.fr',
            'password' => ($password = Hash::make('password'))
        ]);

        $response = $this->post(route('login.perform'), [
            'username' => $user->email,
            'password' => $password
        ]);

        $response->assertRedirect(route('user.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_failed() :void
    {
        $user = User::factory()->create([
            'email' => 'test@test.fr',
            'password' => Hash::make('password')
        ]);

        $response = $this->from(route('login'))->post(route('login.perform'), [
            'username' => $user->email,
            'password' => 'invalid password'
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Invalid login credentials. Please try again or reset your password. <br><a class="text-danger fw-bold text-decoration-none" href="/contact">Contact</a> our support team for help.');

        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertGuest();
    }

    public function test_login_email_not_verified() :void
    {
        $user = User::factory()->create([
            'email' => 'test@test.fr',
            'password' => ($password = Hash::make('password')),
            'email_verified_at' => null
        ]);

        $response = $this->from(route('login'))->post(route('login.perform'), [
            'username' => $user->email,
            'password' => $password
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Your email is not verified. Please check your mailbox');

        $this->assertTrue(session()->hasOldInput('username'));
        $this->assertGuest();
    }

    public function test_login_banned_user() :void
    {
        $user = User::factory()->create([
            'email' => 'test@test.fr',
            'password' => ($password = Hash::make('password')),
            'banned_at' => now()
        ]);

        $response = $this->from(route('login'))->post(route('login.perform'), [
            'username' => $user->email,
            'password' => $password
        ]);

        $response->assertRedirect(route('user.index'));

        $response = $this->get(route('user.index'));

        $response->assertRedirect(route('login'));

        $response->assertSessionHas('error', 'Your account has been suspended for violating our <a target="_blank" class="text-danger fw-bold" href="/terms">Terms of Service</a>');

        $this->assertGuest();
    }
}
