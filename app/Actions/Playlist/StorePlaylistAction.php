<?php

namespace App\Actions\Playlist;

use App\Http\Requests\Playlist\StorePlaylistRequest;
use App\Models\Playlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StorePlaylistAction
{
    public function execute(StorePlaylistRequest $request): void
    {
        $validated = $request->safe()->merge([
            'uuid' => Str::uuid()->toString(),
            'user_id' => Auth::user()->id
        ])->toArray();

        $playlist = Playlist::create($validated);

        $this->syncVideos($playlist, $request->array('videos'));
    }

    private function syncVideos (Playlist $playlist, array $videosIds): void
    {
        foreach ($videosIds as $index => $id) {
            $playlist->videos()->attach([
                $id => ['position' => $index]
            ]);
        }
    }
}
