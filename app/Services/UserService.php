<?php

namespace App\Services;

use App\Enums\PlaylistStatus;
use App\Models\Playlist;
use App\Models\User;
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

    public function createDefaultPlaylists(User $user): void
    {
        $user->playlists()->create([
            'uuid' => (string) Str::uuid(),
            'title' => Playlist::WATCH_LATER_PLAYLIST,
            'status' => PlaylistStatus::PRIVATE,
            'is_deletable' => false
        ]);
    }
}
