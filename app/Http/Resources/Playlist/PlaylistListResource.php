<?php

namespace App\Http\Resources\Playlist;

use App\Models\Playlist;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @mixin Playlist
 */
class PlaylistListResource extends JsonResource
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
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => Str::limit($this->title, 90),
            'thumbnail' => $this->first_video?->thumbnail_url,
            'icon' => $this->status->icon(),
            'status' => $this->status->name,
            'has_video' => $this->whenHas('has_video'),
            'videos_count' => $this->whenCounted('videos'),
        ];
    }
}
