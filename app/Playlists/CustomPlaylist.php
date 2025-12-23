<?php

namespace App\Playlists;

use App\Models\Playlist;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

abstract class CustomPlaylist
{
    protected ?Playlist $playlist = null;

    public function __construct()
    {
        $this->playlist = $this->initPlaylist();
    }

    /**
     * Get the display name for this playlist
     */
    abstract public static function getName(): string;

    /**
     * Get videos for this playlist
     */
    abstract public function getVideos(): Collection;

    abstract public function getActions(): Collection;

    /**
     * Get the playlist model
     */
    public function getPlaylist(): Playlist
    {
        return $this->playlist;
    }

    /**
     * Get or create the playlist record
     */
    protected function initPlaylist(): Playlist
    {
        return Playlist::query()
            ->where('user_id', Auth::user()->id)
            ->where('title', $this->getName())
            ->first();
    }
}
