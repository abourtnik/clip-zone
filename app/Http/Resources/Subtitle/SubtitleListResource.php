<?php

namespace App\Http\Resources\Subtitle;

use App\Models\Subtitle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Subtitle
 */
class SubtitleListResource extends JsonResource
{

    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'video_id' => $this->video->uuid,
            'name' => $this->name,
            'language' => $this->language,
            'file_name' => $this->file_name,
            'file_url' => $this->download_url,
            'status' => $this->status,
            'is_generated' => !$this->is_uploaded,
            'is_uploaded' => $this->is_uploaded,
            'is_enabled' => $this->is_enabled,
            'is_empty' => $this->is_empty,
            'created_at' => $this->created_at,
        ];
    }
}
