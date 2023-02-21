<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Contracts\View\View;

class PlaylistController
{
    public function show (Playlist $playlist) : View {
        return view('playlists.show', [
            'playlist' => $playlist
                ->load(['videos' => fn($q) => $q->withCount('views')->with('user')])
                ->loadCount('videos')
        ]);
    }
}
