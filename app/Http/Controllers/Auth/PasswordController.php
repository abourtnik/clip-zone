<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PasswordController
{
    public function forgot(): View {
        return view('auth.password.forgot');
    }

    public function email (Request $request) : RedirectResponse {

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records',
        ])->onlyInput('username');
    }

    public function reset (Request $request) : View {
        return view('auth.password.reset');
    }

    public function update (Request $request) : RedirectResponse {

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records',
        ])->onlyInput('username');
    }
}
