<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class RegistrationController
{
    public function show(): View {
        return view('auth.register');
    }

    public function register (RegisterUserRequest $request) : RedirectResponse {

        $validated = $request
            ->safe()
            ->merge(['is_admin' => null])
            ->except('cgu');

        $user = User::create($validated);

        event(new Registered($user));

        return redirect(route('login'))->with('success', __('registration.confirmation', ['email' => $user->email]));
    }

    public function confirm ($id, $token) : RedirectResponse {

        $user = User::where(['id' => $id, 'confirmation_token' => $token])->first();

        if (!$user) {
            return redirect(route('login'))->with('error', __('registration.link_invalid'));
        }

        if ($user->created_at->addMinutes(Config::get('auth.verification.expire', 60))->lt(now())) {
            return redirect(route('login'))->with('error', __('registration.link_expired'));
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        Auth::login($user);

        return redirect()->route('pages.home');
    }
}
