<?php

namespace App\Actions\Video;

use App\Data\NextVideoDTO;
use App\Models\Playlist;
use App\Models\Video;
use App\Services\VideoService;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class ShowVideoAction
{
    private Video $video;
    private ?Playlist $playlist = null;
    private ?int $currentIndex = null;
    private ?Collection $playlistVideos = null;

    public function __construct(private readonly VideoService $videoService)
    {
    }

    public function data(Video $video): array
    {
        $this->video = $video;

        $this->playlist = $this->getPlaylist();

        $suggestedVideos = $this->getSuggestedVideos();

        $nextVideo = $this->getNextVideo($suggestedVideos);

        return [
            'video' => $this->getVideoData(),
            'videos' => $suggestedVideos,
            'nextVideo' => $nextVideo,
            'playlist' => $this->playlist,
            'playlistVideos' => $this->playlistVideos,
            'currentIndex' => $this->currentIndex,
        ];
    }

    private function getSuggestedVideos(): Collection
    {
        return $this->videoService->getSuggestedVideos($this->video);
    }

    private function getNextVideo(Collection $suggestedVideos): NextVideoDTO|null
    {
        return $this->videoService->getNextVideo($suggestedVideos, $this->playlist, $this->playlistVideos, $this->currentIndex);
    }

    private function getVideoData(): Video
    {
        return $this->video
            ->load([
                'user' => fn(BelongsTo $query) => $query->withCount('subscribers'),
                'reportByAuthUser'
            ])
            ->loadCount([
                'likes',
                'dislikes',
                'comments'
            ])
            ->loadExists([
                'likes as liked_by_auth_user' => fn($query) => $query->where('user_id', Auth::id()),
                'dislikes as disliked_by_auth_user' => fn($query) => $query->where('user_id', Auth::id()),
            ]);
    }

    private function getPlaylist(): ?Playlist
    {
        $playlistUuid = request()->string('list');

        if ($playlistUuid->isEmpty()) {
            return null;
        }

        $playlist = Playlist::query()
            ->where('uuid', $playlistUuid->value())
            ->where(function (EloquentBuilder $query) {
                $query->active()->orWhere('user_id', Auth::id());
            })
            ->withCount('videos')
            ->first();

        if (!$playlist) {
            return null;
        }

        $this->loadPlaylistVideos($playlist);

        return $playlist;
    }

    private function loadPlaylistVideos(Playlist $playlist): void
    {
        $this->playlistVideos = $playlist
            ->videos()
            ->with('user')
            ->get();

        // if index not found search return false but is transformed to 0 because currentIndex is int
        $this->currentIndex = $this->playlistVideos
            ->search(fn(Video $video) => $video->id === $this->video->id);
    }
}
