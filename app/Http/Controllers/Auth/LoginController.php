<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController
{
    public function show(): View {
        return view('auth.login');
    }

    public function login (LoginRequest $request) : RedirectResponse {

        $credentials = $request->validated();

        $remember = $request->has('remember');

        $user = User::where('username', $credentials['username'])->orWhere('email', $credentials['username'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {

            Auth::login($user, $remember);

            session()->put('app_locale', $user->language);

            return redirect()->intended(route('user.index'));
        }

        return back()->with('error', __('auth.failed'))->onlyInput('username');
    }

    /**
     * Log the user out of the application.
     *
     * @param Request  $request
     * @return RedirectResponse
     */
    public function logout (Request $request): RedirectResponse {

        $locale = session('app_locale');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($locale) {
            session()->put('app_locale', $locale);
        }

        return redirect()->route('pages.home');
    }
}
