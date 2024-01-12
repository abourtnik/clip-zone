<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController
{
    public function show(): View {
        return view('auth.login');
    }

    public function login (Request $request) : RedirectResponse {

        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);

        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {

            $seconds = RateLimiter::availableIn($this->throttleKey());

            return back()->with('error', __('throttle.auth', ['seconds' => $seconds]))->onlyInput('username');
        }

        $remember = $request->has('remember');

        $user = User::where('username', $credentials['username'])->orWhere('email', $credentials['username'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {

            RateLimiter::clear($this->throttleKey());

            if (!$user->hasVerifiedEmail()) {
                return back()->with('error', __('auth.email_not_verified'))->onlyInput('username');
            }

            Auth::login($user, $remember);

            return redirect()->intended(route('user.index'));
        }

        RateLimiter::hit($this->throttleKey(), 60);

        return back()->with('error', __('auth.failed'))->onlyInput('username');
    }

    /**
     * Log the user out of the application.
     *
     * @param Request  $request
     * @return RedirectResponse
     */
    public function logout (Request $request): RedirectResponse {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('pages.home');
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    private function throttleKey(): string
    {
        return Str::lower(request('email')) . '|' . request()->ip();
    }
}
