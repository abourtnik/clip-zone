<?php

namespace App\Http\Resources\Playlist;

use App\Http\Resources\User\UserListResource;
use App\Http\Resources\Video\VideoListResource;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * @mixin Playlist
 */
class PlaylistShowResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        $playlistVideos = $this->videos()->with('user')->get();

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => Str::limit($this->title, 90),
            'thumbnail' => $this->first_video?->thumbnail_url,
            'description' => $this->description,
            'icon' => $this->status->icon(),
            'is_active' => $this->is_active,
            'route' => $this->route,
            'videos_count' => $playlistVideos->count(),
            'user' => UserListResource::make($this->user),
            'videos' => VideoListResource::collection($playlistVideos)
        ];
    }
}
