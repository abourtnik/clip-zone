<?php

namespace App\Http\Resources\Video;

use App\Models\Video;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @mixin Video
 */
class MyVideoListResource extends JsonResource
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
        $date = $this->publication_date;

        if ($this->is_private || $this->is_unlisted || $this->is_draft|| $this->is_failed) {
            $date = $this->created_at;
        } elseif ($this->is_planned) {
            $date = $this->scheduled_date;
        }

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'route' => $this->route,
            'short_title' => $this->short_title,
            'thumbnail' => $this->thumbnail_url,
            'formated_duration' => $this->duration,
            'views' => $this->views,
            'date' => $date,
            'is_public' => $this->is_public,
            'status' => $this->status,
            'like_count' => $this->likes_count,
            'comments_count' => $this->comments_count
        ];
    }
}
