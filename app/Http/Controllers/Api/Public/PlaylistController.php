<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Resources\Playlist\PlaylistShowResource;
use App\Http\Resources\Video\VideoListResource;
use App\Models\Playlist;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PlaylistController
{
    public function show(Playlist $playlist): PlaylistShowResource
    {
        return new PlaylistShowResource($playlist);
    }

    public function videos(Playlist $playlist): ResourceCollection
    {
        return VideoListResource::collection(
            $playlist->videos()
                ->with('user')
                ->paginate(24)
        );
    }
}
