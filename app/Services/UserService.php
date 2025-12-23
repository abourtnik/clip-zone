<?php

namespace App\Services;

use App\Enums\PlaylistStatus;
use App\Models\User;
use App\Playlists\PlaylistManager;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserService {

    public function register(array $data): User
    {
        $user = User::create($data);

        $this->createDefaultPlaylists($user);

        event(new Registered($user));

        Auth::login($user);

        return $user;
    }

    private function createDefaultPlaylists(User $user): void
    {
        foreach (PlaylistManager::$types as $playlist) {
            $user->playlists()->create([
                'uuid' => Str::uuid()->toString(),
                'title' => $playlist::getName(),
                'status' => PlaylistStatus::PRIVATE,
                'is_deletable' => false
            ]);
        }
    }
}
