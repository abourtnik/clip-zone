<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\Account\VerifyUpdatedEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;

class AccountTest extends TestCase
{
    use RefreshDatabase;
    public function test_update_email() :void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->fromRoute('user.edit')->actingAs($user)->put(route('user.update'), [
            'email' => 'new_email@example.com',
        ])->assertRedirectToRoute('user.edit');

        Notification::assertSentTo(
            [$user], VerifyUpdatedEmail::class
        );
    }
}
