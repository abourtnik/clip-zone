<?php

namespace App\Http\Controllers\Auth;

use App\Actions\User\StoreUserAction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as SocialUser;

class OAuthController
{
    public function __construct(
        protected StoreUserAction $storeUserAction,
    ){}

    public function connect(string $service): RedirectResponse {
        return Socialite::driver($service)
            ->with(['auth_type' => 'rerequest'])
            ->redirect();
    }

    public function callback(string $service): RedirectResponse {

        $route = match (Auth::check()) {
            true => 'user.edit',
            false => 'login'
        };

        $user = Socialite::driver($service)->user();

        if (!$user->getEmail()) {
            return redirect()->route($route)->with(['error' => __('Please agree to give your email')]);
        }

        return match (Auth::check()) {
            true => $this->link($service, $user),
            false => $this->register($service, $user)
        };
    }

    public function unlink(string $service): RedirectResponse {

        $service_id = $service.'_id';

        Auth::user()->update([
            $service_id => null
        ]);

        return redirect()
            ->route('user.edit')
            ->with(['status' => __('Your account has been successfully unlinked from :service', ['service' => Str::ucfirst($service)])]);
    }

    public function link(string $service, SocialUser $socialUser): RedirectResponse {

        $serviceId = $service.'_id';

        $user = User::query()
            ->where($serviceId, $socialUser->getId())
            ->exists();

        if ($user) {
            return redirect()
                ->route('user.edit')
                ->with(['error' => __('This :service account is already linked to another user', ['service' => Str::ucfirst($service)])]);
        }

        Auth::user()->update([
            $service.'_id' => $socialUser->getId()
        ]);

        return redirect()
            ->route('user.edit')
            ->with(['status' => __('Your account has been successfully linked to :service', ['service' => Str::ucfirst($service)])]);

    }

    public function register (string $service, SocialUser $socialUser) : RedirectResponse {

        $serviceId = $service.'_id';

        $user = User::query()->where($serviceId, $socialUser->getId())->first()
            ?? User::query()->where('email', $socialUser->getEmail())->first()
            ?? $this->createUser($serviceId, $socialUser);

        $user->update([
            $serviceId => $socialUser->getId(),
            'email_verified_at' => $user->email_verified_at ?? now(),
        ]);

        Auth::login($user, true);

        return redirect()->intended(route('user.index'));
    }

    private function createUser(string $serviceId, SocialUser $socialUser): User
    {
        $socialUsername = $socialUser->getNickname()
            ?? $socialUser->getName()
            ?? Str::before($socialUser->getEmail(), '@');

        $username = User::query()->where('username', $socialUsername)->exists()
            ? $socialUsername . '-' . Str::lower(Str::random(6))
            : $socialUsername;

        $user = $this->storeUserAction->execute([
            'username' => $username,
            'password' => Str::random(32),
            'email' => $socialUser->getEmail(),
            'email_verified_at' => now(),
            $serviceId => $socialUser->getId(),
        ]);

        $this->downloadAvatar($user, $socialUser->getAvatar());

        return $user;
    }

    private function downloadAvatar(User $user, string|null $avatar): void
    {
        if (!$avatar) {
            return;
        }

        $response = Http::timeout(5)->get($avatar);

        if ($response->successful()) {
            $fileName = Str::random(40) . '.png';
            Storage::put(User::AVATAR_FOLDER.'/'.$fileName, $response->body());
            $user->update(['avatar' => $fileName]);
        }
    }
}
