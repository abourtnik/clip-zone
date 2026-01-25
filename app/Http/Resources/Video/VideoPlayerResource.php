<?php

namespace App\Http\Resources\Video;

use App\Enums\VideoStatus;
use App\Http\Resources\SubtitleResource;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin Video
 */
class VideoPlayerResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request) : array
    {
        return [
            'thumbnail' => $this->thumbnail_url,
            'file' => $this->file_url,
            'show_ad' => $this->show_ad && !Auth::user()?->is_premium && $this->status === VideoStatus::PUBLIC,
            'subtitles' => SubtitleResource::collection($this->subtitles),
        ];
    }
}
