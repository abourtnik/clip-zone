<?php

namespace App\Http\Resources;

use App\Models\Thumbnail;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @mixin Thumbnail
 */
class ThumbnailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'status' => $this->status,
            'is_active' => $this->is_active,
        ];
    }
}
