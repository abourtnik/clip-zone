<?php

namespace App\Http\Resources;

use App\Models\Playlist;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @mixin Playlist
 */
class PlaylistResource extends JsonResource
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
            'title' => Str::limit($this->title, 90),
            'icon' => $this->status->icon(),
            'has_video' => $this->resource->has_video
        ];
    }
}
