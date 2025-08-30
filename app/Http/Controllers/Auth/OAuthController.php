<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as SocialUser;

class OAuthController
{
    public function __construct(
        protected UserService $userService,
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

        try {
            $user = Socialite::driver($service)->user();
        } catch (\Exception $e) {
            return redirect()->route($route);
        }

        if (!$user->getEmail()) {
            return redirect()->route($route)->with(['error' => 'Please agree to give your email']);
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

        return redirect()->route('user.edit')->with(['status' => 'Your account has been successfully unlinked from '.ucfirst($service)]);
    }

    public function link(string $service, SocialUser $user): RedirectResponse {

        if (User::where($service.'_id', $user->getId())->exists()) {
            return redirect()->route('user.edit')->with(['error' => 'This '.ucfirst($service).' account is already linked to another user']);
        }

        Auth::user()->update([
            $service.'_id' => $user->getId()
        ]);

        return redirect()->route('user.edit')->with(['status' => 'Your account has been successfully linked to '.ucfirst($service)]);
    }

    public function register (string $service, SocialUser $socialUser) : RedirectResponse {

        $service_id = $service.'_id';

        $user = User::where($service_id, $socialUser->getId())->orWhere('email', $socialUser->getEmail())->first();

        if (!$user) {

            $socialUsername = $socialUser->getNickname() ?? $socialUser->getName();

            // Check if username already exist
            $usernameExist = User::where('username', $socialUsername)->exists();

            $username = $usernameExist ? $socialUsername .'-'. random_int(10, 1000)  : $socialUsername;

            $user = $this->userService->register([
                'username' => $username,
                'slug' => User::generateSlug($username),
                'password' => '',
                'email' => $socialUser->getEmail(),
                'email_verified_at' => Carbon::now(),
                $service_id => $socialUser->getId(),
            ]);

            if ($socialUser->getAvatar()) {

                $fileName = Str::of(uniqid())->pipe('md5') . '.png';
                Storage::put(User::AVATAR_FOLDER.'/'.$fileName, file_get_contents($socialUser->getAvatar()));

                $user->update([
                    'avatar' => $fileName
                ]);
            }
        }

        else {
            $user->update([
                $service_id => $socialUser->getId(),
            ]);
        }

        Auth::login($user);

        return redirect()->intended(route('user.index'));
    }
}
