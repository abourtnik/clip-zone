<?php

namespace Tests\Feature;

use App\Notifications\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;


class ContactTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_contact_view() :void
    {
        $response = $this->get(route('contact.show'));

        $response
            ->assertOk()
            ->assertViewIs('contact.show');
    }

    public function test_send_email_success() :void
    {
        Notification::fake();

        $response = $this->post(route('contact.contact', [
            'name' => 'Anton',
            'email' => 'test@test.fr',
            'message' => 'aaaaaaaaaa'
        ]));

        Notification::assertSentTimes(Contact::class, 1);

        $response
            ->assertRedirectToRoute('contact.show')
            ->assertSessionHas('success', "Thank you for contacting us! We've received your message and will get back to you shortly.");
    }
}
