<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegistrationController
{
    public function show(): View
    {
        return view('auth.register');
    }

    public function register(RegisterUserRequest $request): RedirectResponse
    {
        $validated = $request
            ->safe()
            ->merge([
                'slug' => User::generateSlug($request->get('username')),
                'is_admin' => null
            ])
            ->except('cgu');

        $user = User::create($validated);

        Auth::login($user);

        event(new Registered($user));

        return redirect()->route('user.edit');
    }
}
