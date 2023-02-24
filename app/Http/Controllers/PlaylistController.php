<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PlaylistController
{
    public function show (Playlist $playlist) : View {
        return view('playlists.show', [
            'playlist' => $playlist
                ->load(['videos' => fn($q) => $q->withCount('views')->with('user')])
                ->loadCount([
                    'videos',
                    'users as favorite_by_auth_user' => fn($q) => $q->where('user_id', Auth::id()),
                ])
        ]);
    }

    public function favorite (Playlist $playlist) : Response {

        Auth::user()->favorites_playlist()->attach($playlist->id);
        return response()->noContent();
    }

    public function removeFavorite (Playlist $playlist) : Response {

        Auth::user()->favorites_playlist()->detach($playlist->id);
        return response()->noContent();
    }
}
