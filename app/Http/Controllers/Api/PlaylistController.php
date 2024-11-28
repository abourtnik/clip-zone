<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Playlist\StorePlaylistRequest;
use App\Http\Requests\Video\SaveRequest;
use App\Http\Resources\Playlist\PlaylistListResource;
use App\Http\Resources\Playlist\PlaylistShowResource;
use App\Http\Resources\Video\VideoListResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Pivots\PlaylistVideo;
use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

    public function videos(Playlist $playlist): ResourceCollection
    {
        return VideoListResource::collection(
            $playlist->videos()
                ->with('user')
                ->withCount('views')
                ->paginate(24)
        );
    }

    public function store(StorePlaylistRequest $request): PlaylistListResource {

        $validated = $request->safe()->merge([
            'uuid' => (string) Str::uuid(),
            'user_id' => Auth::user()->id
        ])->toArray();

        $playlist = Playlist::create($validated);

        return new PlaylistListResource($playlist);
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
