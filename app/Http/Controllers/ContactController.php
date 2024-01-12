<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contact\ContactRequest;
use App\Notifications\Contact;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact.show');
    }

    public function contact (ContactRequest $request): RedirectResponse {

        if (RateLimiter::tooManyAttempts($this->throttleKey(), 1)) {

            $seconds = RateLimiter::availableIn($this->throttleKey());

            return back()->with('error', __('throttle.contact', ['seconds' => $seconds]))->withInput();
        }

        list('name' => $name, 'email' => $email, 'message' => $message) = $request->only('name', 'email', 'message');

        Notification::route('mail', [
            config('mail.support_mail') => 'Anton Bourtnik',
        ])->notify(new Contact($name, $email, $message));

        RateLimiter::hit($this->throttleKey(), 60);

        return redirect()->route('contact.show')
            ->with('success', "Thank you for contacting us! We've received your message and will get back to you shortly.");

    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    private function throttleKey(): string
    {
        return request()->ip();
    }
}
