<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class RegistrationController
{
    public function show(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request, UserService $userService): RedirectResponse
    {
        $validated = $request
            ->safe()
            ->merge([
                'slug' => User::generateSlug($request->get('username')),
                'is_admin' => null
            ])
            ->except('cgu');

        $userService->register($validated);

        return redirect()->route('user.edit');
    }
}
