<?php

namespace App\Http\Controllers\Auth;

use App\Actions\User\StoreUserAction;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RegistrationController
{
    public function show(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request, StoreUserAction $storeUserAction): RedirectResponse
    {
        $user = $storeUserAction->execute($request->safe()->except('cgu'));

        Auth::login($user, true);

        return redirect()->route('user.edit');
    }
}
