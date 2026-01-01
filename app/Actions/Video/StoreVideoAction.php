<?php

namespace App\Actions\Video;

use App\Enums\VideoStatus;
use App\Http\Requests\Video\StoreVideoRequest;
use App\Models\Playlist;
use App\Models\Video;
use App\Services\ThumbnailService;

class StoreVideoAction
{
    public function execute(StoreVideoRequest $request, Video $video): void
    {
        $validated = $request->safe()->merge([
            'slug' => $request->string('title')->slug(),
            'scheduled_at' => $request->date('scheduled_at'),
            'published_at' => $this->determinePublishedAt($request)
        ])->toArray();

        $video->update($validated);

        $this->syncPlaylists($video, $request->array('playlists'));

        ThumbnailService::save($request);
    }

    private function determinePublishedAt(StoreVideoRequest $request): ?string
    {
        return match($request->integer('status')) {
            VideoStatus::PUBLIC->value => now(),
            VideoStatus::PLANNED->value =>  $request->date('scheduled_at'),
            default => null,
        };
    }

    private function syncPlaylists (Video $video, array $playlistIds): void
    {
        $playlists = Playlist::query()
            ->whereIn('id', $playlistIds)
            ->withMax('videos as last_position', 'playlist_has_videos.position')
            ->get();

        foreach ($playlists as $playlist) {
            $playlist->videos()->attach([
                $video->id => ['position' => is_null($playlist->last_position) ? 0 : $playlist->last_position + 1]
            ]);
        }
    }
}
