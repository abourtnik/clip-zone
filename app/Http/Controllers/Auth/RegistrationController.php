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
            ->merge(['is_admin' => false])
            ->except('cgu');

        $user = User::create($validated);

        event(new Registered($user));

        return redirect(route('login'))->with('success', 'A message with a confirmation link has been sent to your e-mail. Please follow this link to activate your account.');
    }

    public function confirm ($id, $token) : RedirectResponse {

        $user = User::where(['id' => $id, 'confirmation_token' => $token])->first();

        if (!$user) {
            return redirect(route('login'))->with('error', 'This link is invalid');
        }

        if ($user->created_at->addMinutes(Config::get('auth.verification.expire', 60))->lt(now())) {
            return redirect(route('login'))->with('error', 'This link is expired');
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            //event(new Verified($user));
        }

        Auth::login($user);

        return redirect()->route('user.index');

    }
}
