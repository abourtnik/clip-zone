<?php

namespace App\Actions\Playlist;

use App\Http\Requests\Playlist\UpdatePlaylistRequest;
use App\Models\Playlist;

class UpdatePlaylistAction
{
    public function execute(UpdatePlaylistRequest $request, Playlist $playlist): void
    {
        $playlist->update($request->validated());

        $this->syncVideos($playlist, $request->array('videos'));

        $playlist->touch();
    }

    private function syncVideos (Playlist $playlist, array $videosIds): void
    {
        $videoSync = [];

        foreach ($videosIds as $index => $id) {
            $videoSync[$id] = ['position' => $index];
        }

        $playlist->videos()->sync($videoSync);
    }
}
