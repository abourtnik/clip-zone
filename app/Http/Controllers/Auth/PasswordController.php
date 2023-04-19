<?php

namespace App\Http\Controllers\Auth;

use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\ResetPassword;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PasswordController
{
    public function forgot(): View {
        return view('auth.password.forgot');
    }

    public function email (Request $request) : RedirectResponse {

        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::exists('users', 'email')->where(function ($query) {
                    return $query->whereNull('banned_at');
                })
            ]
        ]);

        $user = User::where('email', $request->get('email'))->first();

        $recentlyCreatedToken = PasswordReset::where('user_id', $user->id)->latest()->first();

        if ($recentlyCreatedToken && !$recentlyCreatedToken->is_expired) {
            return back()->withErrors(['email' => 'A reset request has already been sent to this email'])->withInput();
        }

        $reset = PasswordReset::create([
            'user_id' => $user->id,
            'token' => Str::random(40),
            'created_at' => now()
        ]);

        $user->notify(new ResetPassword($reset->token));

        return back()->with(['status' => 'A message with a reset link has been sent to your e-mail. Please follow this link to reset your password.']);

    }

    public function reset (string $id, string $token) : View|RedirectResponse {

        $this->checkToken($id, $token);

        return view('auth.password.reset', [
            'id' => $id,
            'token' => $token,
        ]);
    }

    public function update (Request $request) : RedirectResponse
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user = $this->checkToken($request->get('id'), $request->get('token'));

        $user->updatePassword($request->get('password'));

        PasswordReset::where([
            'user_id' => $request->get('id'),
        ])->delete();

        return redirect(route('login'))->with('success', 'Your password has been reset successfully');

    }

    private function checkToken (string $user_id, string $token): RedirectResponse|User {

        $reset = PasswordReset::where([
            'user_id' => $user_id,
            'token' => $token
        ])->latest()->first();

        if (!$reset || $reset->is_expired) {
            return redirect(route('login'))->with('error', 'This link is expired or invalid');
        }

        return $reset->user;
    }
}
