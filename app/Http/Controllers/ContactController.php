<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contact\ContactRequest;
use App\Notifications\Contact;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact.show');
    }

    public function contact (ContactRequest $request): RedirectResponse {

        list('name' => $name, 'email' => $email, 'message' => $message) = $request->only('name', 'email', 'message');

        Notification::route('mail', [
            config('mail.support_mail') => 'Anton Bourtnik',
        ])->notify(new Contact($name, $email, $message));

        return redirect()->route('contact.show')
            ->with('success', "Thank you for contacting us! We've received your message and will get back to you shortly.");

    }
}
