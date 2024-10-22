<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Video\VideoListResource;
use App\Models\Playlist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserShowResource extends JsonResource
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
            'username' => $this->username,
            'slug' => $this->slug,
            'avatar' => $this->avatar_url,
            'banner' => $this->banner_url,
            'show_subscribers' => $this->show_subscribers,
            $this->mergeWhen($this->show_subscribers, [
                'subscribers' => $this->subscribers_count
            ]),
            'videos_count' => $this->videos_count,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'website' => $this->website,
            'country_code' => $this->country,
            'country' => $this->country_name,
            'views' => $this->whenCounted('videos_views'),
            'created_at' => $this->created_at,
            'pinned_video' => $this->when(!is_null($this->pinned_video), function () {
                return VideoListResource::make($this->pinned_video);
            }, null),
            'videos' => VideoListResource::collection($this->videos),
            'playlists' => $this->playlists->map(function (Playlist $playlist) {
                return [
                    'id' => $playlist->id,
                    'title' => $playlist->title,
                    'videos' => VideoListResource::collection($playlist->videos),
                ];
            }),

        ];
    }
}
