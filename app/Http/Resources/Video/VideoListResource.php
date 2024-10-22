<?php

namespace App\Http\Resources\Video;

use App\Http\Resources\User\UserListResource;
use App\Models\Video;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin Video
 */
class VideoListResource extends JsonResource
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
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'short_title' => $this->short_title,
            'thumbnail' => $this->thumbnail_url,
            'formated_duration' => $this->duration,
            'views' => $this->views_count,
            'route' => $this->route,
            'publication_date' => $this->publication_date,
            'user' => UserListResource::make($this->user),
            'is_private' => $this->when($this->user->isNot(Auth::user()) && !$this->is_public, true),
        ];
    }
}
