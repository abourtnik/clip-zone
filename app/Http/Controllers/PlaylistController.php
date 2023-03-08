<?php

namespace App\Http\Controllers;

use App\Enums\VideoStatus;
use App\Models\Playlist;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PlaylistController
{
    public function show (Playlist $playlist) : View {
        return view('playlists.show', [
            'playlist' => $playlist
                ->load([
                    'videos' => fn($q) => $q->when($playlist->user()->isNot(Auth::user()), fn($q) => $q->active()->orWhere('status', VideoStatus::UNLISTED))
                        ->withCount('views')
                        ->with('user')
                ])
                ->loadCount([
                    'videos',
                    'videos as hidden_videos_count' => fn($q) => $q->notActive(),
                ]),
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

    public function favorite (Playlist $playlist) : Response {

        Auth::user()->favorites_playlist()->attach($playlist->id);
        return response()->noContent();
    }

    public function removeFavorite (Playlist $playlist) : Response {

        Auth::user()->favorites_playlist()->detach($playlist->id);
        return response()->noContent();
    }
}
