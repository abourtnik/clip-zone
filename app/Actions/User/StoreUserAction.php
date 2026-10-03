<?php

namespace App\Actions\User;

use App\Enums\PlaylistStatus;
use App\Helpers\Locale;
use App\Models\User;
use App\Playlists\PlaylistManager;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

class StoreUserAction
{
    public function execute(array $data): User
    {
        $user = User::create([
            ...$data,
            ...$this->prepareData($data)
        ]);

        $this->createDefaultPlaylists($user);

        event(new Registered($user));

        return $user;
    }

    private function prepareData (array $data): array
    {
        return [
            'slug' => User::generateSlug($data['username']),
            'is_admin' => false,
            'language' => Locale::resolve(request())
        ];
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
