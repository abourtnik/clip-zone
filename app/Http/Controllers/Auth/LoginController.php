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

        $user = User::where('username', $credentials['username'])->orWhere('email', $credentials['username'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);

            if ($user->isAdministrator()) {
                return redirect()->intended('admin');
            }else {
                return redirect()->intended('account');
            }

        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records',
        ])->onlyInput('username');
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

        return redirect('/');
    }
}
