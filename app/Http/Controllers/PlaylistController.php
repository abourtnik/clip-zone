<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class PlaylistController
{
    public function show (Playlist $playlist) : View {
        return view('playlists.show', [
            'playlist' => $playlist,
            'videos' => $playlist
                ->videos()
                ->with('user')
                ->get(),
            'favorite_by_auth_user' => Auth::user()?->favorites_playlist()->where('playlist_id', $playlist->id)->exists()
        ]);
    }

    public function manage (): View {
        return view('playlists.manage', [
            'playlists' => Auth::user()->favorites_playlist()
                ->with('user')
                ->withCount('videos')
                ->latest('added_at')
                ->get()
        ]);
    }
}
