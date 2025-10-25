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
        $isPrivate = $this->user->isNot(Auth::user()) && !$this->is_public;

        return [
            'id' => $this->id,
            $this->mergeWhen(!$isPrivate, [
                'uuid' => $this->uuid,
                'title' => $this->title,
                'short_title' => $this->short_title,
                'thumbnail' => $this->thumbnail_url,
                'formated_duration' => $this->duration,
                'views' => $this->views,
                'route' => $this->route,
                'published_at' => $this->published_at,
                'user' => UserListResource::make($this->user),
            ]),
            'is_private' => $this->when($isPrivate, true),
        ];
    }
}
