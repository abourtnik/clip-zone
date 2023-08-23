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
                return back()->with('error', 'Your email is not verified. Please check your mailbox')->onlyInput('username');
            }

            Auth::login($user, $remember);

            return redirect()->intended(route('user.index'));
        }

        return back()->with('error', 'Invalid login credentials. Please try again or reset your password. <br><a class="text-danger fw-bold text-decoration-none" href="/contact">Contact</a> our support team for help.')->onlyInput('username');
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
