<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Video\SaveRequest;
use App\Http\Resources\Playlist\PlaylistShowResource;
use App\Models\Pivots\PlaylistVideo;
use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Http\JsonResponse;

class PlaylistController
{
    public function show(Playlist $playlist): PlaylistShowResource
    {
        return new PlaylistShowResource(
            $playlist->load([
                'videos' => fn($q) => $q->withCount('views')->with('user')
            ])->loadCount([
                'videos'
            ])
        );
    }

    public function save (SaveRequest $request): JsonResponse {

        $video = Video::findOrFail($request->get('video_id'));

        $playlists = $request->get('playlists', []);

        foreach ($playlists as $playlist) {

            if ($playlist['checked']) {

                $exist = PlaylistVideo::where([
                    'playlist_id' => $playlist['id'],
                    'video_id' => $video->id
                ])->exists();

                if (!$exist) {

                    $lastPosition = PlaylistVideo::where('playlist_id', $playlist['id'])
                        ->latest('position')
                        ->first()
                        ?->position;

                    PlaylistVideo::create([
                        'playlist_id' => $playlist['id'],
                        'video_id' => $video->id,
                        'position' => is_null($lastPosition) ? 0 : $lastPosition + 1
                    ]);
                }

            }

            else {
                PlaylistVideo::where([
                    'playlist_id' => $playlist['id'],
                    'video_id' => $video->id
                ])->delete();
            }
        }

        return response()->json(null, 201);
    }

}
