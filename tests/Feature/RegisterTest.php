<?php

namespace Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $response = $this->get(route('registration'));

        $response->assertViewIs('auth.register');
    }

    public function test_register_success() :void
    {
        $response = $this->post(route('registration.perform'), [
            'username' => 'test',
            'email' => 'test@test.fr',
            'password' => 'aaaaaaaa',
            'password_confirmation' => 'aaaaaaaa',
            'cgu' => true,
        ]);

        $this->assertDatabaseCount('users', 1);

        $this->assertDatabaseHas('users', [
            'email' =>'test@test.fr',
        ]);

        $response
            ->assertRedirectToRoute('login')
            ->assertSessionHas('success');
    }
}
