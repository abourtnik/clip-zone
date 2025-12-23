<?php

namespace App\Http\Controllers\Api\Private;

use App\Http\Requests\Playlist\StorePlaylistRequest;
use App\Http\Requests\Video\SaveRequest;
use App\Http\Resources\Playlist\PlaylistListResource;
use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PlaylistController
{
    public function store(StorePlaylistRequest $request): PlaylistListResource {

        $validated = $request->safe()->merge([
            'uuid' => Str::uuid()->toString(),
            'user_id' => Auth::user()->id
        ])->toArray();

        $playlist = Playlist::create($validated);

        return new PlaylistListResource($playlist);
    }

    public function save (SaveRequest $request): JsonResponse {

        $video = Video::query()
            ->findOrFail($request->get('video_id'));

        $playlist = Playlist::query()
            ->withMax('videos as last_position', 'playlist_has_videos.position')
            ->findOrFail($request->get('playlist_id'));

        $playlist->videos()->toggle([
            $video->id => ['position' => is_null($playlist->last_position) ? 0 : $playlist->last_position + 1]
        ]);

        $playlist->touch();

        return response()->json(null, 201);
    }

    public function delete(Playlist $playlist): Response
    {
        $playlist->delete();

        return response()->noContent();
    }

}
