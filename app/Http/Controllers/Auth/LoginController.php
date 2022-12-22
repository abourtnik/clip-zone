<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;


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

        $remember = $request->has('remember');

        $user = User::where('username', $credentials['username'])->orWhere('email', $credentials['username'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {

            if (!$user->hasVerifiedEmail()) {
                return back()->with('error', 'Your email is not verified. Please check your email')->onlyInput('username');
            }

            Auth::login($user, $remember);

            if ($user->isAdministrator()) {
                return redirect()->intended('admin');
            }else {
                return redirect()->intended('profile.index');
            }
        }

        return back()->with('error', 'The provided credentials do not match our records')->onlyInput('username');
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
}
