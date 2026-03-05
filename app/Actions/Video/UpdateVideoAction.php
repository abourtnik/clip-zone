<?php

namespace App\Actions\Video;

use App\Enums\VideoStatus;
use App\Helpers\File;
use App\Http\Requests\Video\UpdateVideoRequest;
use App\Models\Playlist;
use App\Models\Subtitle;
use App\Models\Video;
use App\Services\ThumbnailService;

class UpdateVideoAction
{
    public function execute(UpdateVideoRequest $request, Video $video): void
    {
        $validated = $request->safe()->merge([
            'slug' => $request->string('title')->slug(),
            'scheduled_at' => $this->determineScheduledAt($request),
            'published_at' => $this->determinePublishedAt($request, $video),
        ])->toArray();

        $video->update($validated);

        $this->syncPlaylists($video, $request->array('playlists'));

        $this->syncSubtitles($video, $request->array('subtitles'), $request->file('subtitles'));

        ThumbnailService::save($request);
    }

    private function determineScheduledAt(UpdateVideoRequest $request): ?string
    {
        return match($request->integer('status')) {
            VideoStatus::PLANNED->value => $request->date('scheduled_at'),
            default => null,
        };
    }

    private function determinePublishedAt(UpdateVideoRequest $request, Video $video): ?string
    {
        // Published date is the first date that video become public, this data never be updated after first publication

        if ($video->published_at?->isPast()) {
            return $video->published_at;
        }

        return match($request->integer('status')) {
            VideoStatus::PUBLIC->value => now(),
            VideoStatus::PLANNED->value => $request->date('scheduled_at'),
            default => null,
        };
    }

    private function syncPlaylists (Video $video, array $playlistIds): void
    {
        $dataToSync = [];

        $playlists = Playlist::query()
            ->whereIn('id', $playlistIds)
            ->withMax('videos as last_position', 'playlist_has_videos.position')
            ->get();

        foreach ($playlistIds as $playlist_id) {

            $playlist = $playlists->where('id', $playlist_id)->first();

            if (!$playlist) {
                continue;
            }

            $playlistVideo = $playlist->videos()->where('video_id', $video->id)->first();

            if ($playlistVideo) {
                $dataToSync[$playlist_id] = ['position' => $playlistVideo->pivot->position];
                continue;
            }

            $dataToSync[$playlist_id] = ['position' => is_null($playlist->last_position) ? 0 : $playlist->last_position + 1];
        }

        $video->playlists()->sync($dataToSync);
    }

    private function syncSubtitles(Video $video, ?array $subtitles, ?array $files): void
    {
        // Delete removed subtitles
        $ids = collect($subtitles)->pluck('id')->filter()->toArray();
        $video->subtitles()
            ->whereNotIn('id', $ids)
            ->get()
            ->each(function(Subtitle $subtitle) {
                File::deleteIfExist($subtitle->file, Subtitle::FILE_FOLDER);
                $subtitle->delete();
            });

        foreach ($subtitles as $index => $subtitle) {

            $data = $subtitle;

            $uploadedFile = isset($files[$index]) ? $files[$index]['file'] : null;

            $initialFile = isset($subtitle['id']) ? $video->subtitles()->find($subtitle['id'])?->file : null;

            $data['file'] = File::storeAndDelete($uploadedFile, Subtitle::FILE_FOLDER, $initialFile);

            $video->subtitles()->updateOrCreate(
                ['id' => $subtitle['id'] ?? null],
                $data
            );
        }
    }
}
