<?php

namespace App\Http\Controllers\User;

use App\Events\Account\EmailUpdated;
use App\Events\Account\PasswordUpdated;
use App\Helpers\File;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Notifications\Account\DeleteAccount;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\Intl\Countries;

class ProfileController
{
    public function edit(): View {
        return view('users.profile.edit', [
            'user' => Auth::user()->load('premium_subscription'),
            'billing_profile_url' => Auth::user()->stripe_id ? Auth::user()->billingPortalUrl(route('user.edit')) : null,
            'countries' => Countries::getNames()
        ]);
    }

    public function update(UpdateUserRequest $request) : RedirectResponse {

        $user = auth()->user();

        $validated = collect($request->safe()->except(['email']))->merge([
            'avatar' => File::storeAndDelete($request->file('avatar'), User::AVATAR_FOLDER, $user->avatar),
            'banner' => File::storeAndDelete($request->file('banner'), User::BANNER_FOLDER, $user->banner),
        ])->toArray();

        $user->update($validated);

        if ($request->get('email') !== $user->email && !$user->getTemporaryEmailForVerification()) {
            EmailUpdated::dispatch($request->get('email'));
        }

        if ($user->wasChanged('phone')) {
            $request->user()->sendPhoneVerificationNotification();
        }

        return redirect()->route('user.edit')->with(['status' => 'Your profile information has been updated successfully !']);
    }

    public function updatePassword(Request $request): RedirectResponse {

        $request->validate([
            'new_password' => ['required', 'confirmed', Password::min(6)]
        ]);

        PasswordUpdated::dispatch($request->get('new_password'));

        return redirect()->route('user.edit')->with(['status' => 'Your password has been updated successfully !']);
    }

    public function delete(): RedirectResponse {

        Auth::user()->notify(new DeleteAccount());

        User::find(Auth::id())->delete();
        Auth::logout();

        return redirect()->route('pages.home');
    }

}
