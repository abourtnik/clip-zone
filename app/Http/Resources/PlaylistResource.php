<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlaylistResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'title' => Str::limit($this->title, 90),
            'icon' => $this->status->icon(),
            'has_video' => $this->whenNotNull($this->has_video)
        ];
    }
}
