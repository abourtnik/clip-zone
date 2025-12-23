<?php

namespace App\Http\Controllers\User;

use App\Enums\CustomPlaylistType;
use App\Http\Controllers\Controller;
use App\Playlists\PlaylistManager;
use App\Playlists\Types\WatchLaterPlaylist;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CustomPlaylistController extends Controller
{

    public function show(CustomPlaylistType $type): View
    {
        $playlistManager = PlaylistManager::get($type);

        $playlist = $playlistManager->getPlaylist();
        $videos = $playlistManager->getVideos();

        $playlist->setRelation('videos', $videos);
        $playlist->setRelation('videos_count', $videos->count());

        return view('playlists.custom', [
            'playlist' => $playlistManager->getPlaylist(),
            'actions' => $playlistManager->getActions(),
        ]);
    }

    /**
     * Remove watched videos from Watch Later playlist
     */
    public function clearWatchedVideos(): RedirectResponse
    {
        $playlistManager = PlaylistManager::get(CustomPlaylistType::WATCH_LATER);

        /* @var WatchLaterPlaylist $playlistManager */
        $playlistManager->removeWatchedVideos();

        $playlistManager->getPlaylist()->touch();

        return redirect()->back();
    }
}
