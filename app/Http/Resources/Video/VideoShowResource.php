<?php

namespace App\Http\Resources\Video;

use App\Http\Resources\User\UserListResource;
use App\Models\Video;
use App\Services\VideoService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Video
 */
class VideoShowResource extends JsonResource
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
            'file' => $this->file,
            'allow_comments' => $this->allow_comments,
            'show_likes' => $this->show_likes,
            $this->mergeWhen($this->show_likes, [
                'likes' => $this->likes_count,
                'dislikes' => $this->dislikes_count,
            ]),
            $this->mergeWhen(auth('sanctum')->check(), [
                'liked_by_auth_user' => $this->resource->liked_by_auth_user,
                'disliked_by_auth_user' => $this->resource->disliked_by_auth_user,
                'reported_by_auth_user' => $this->resource->reported_by_auth_user,
                $this->mergeWhen($this->resource->reported_by_auth_user, [
                    'report_at' => $this->resource->reportByAuthUser?->created_at
                ])
            ]),
            'default_comments_sort' => $this->default_comments_sort,
            'title' => $this->title,
            'short_title' => $this->short_title,
            'short_description' => nl2br($this->short_description),
            'description' => nl2br($this->parsed_description),
            'description_is_long' => $this->description_is_long,
            'thumbnail' => $this->thumbnail_url,
            'duration' => (int) $this->getRawOriginal('duration'),
            'formated_duration' => $this->duration,
            'views' => $this->views_count,
            'route' => $this->route,
            'user' => UserListResource::make($this->user),
            'comments' => $this->comments_count,
            'publication_date' => $this->publication_date ?? $this->created_at,
            'status' => $this->status,
            'first_comment' => $this->when($this->comments_count , function () {
                return [
                    'content' => $this->comments->first()->content,
                    'user_avatar' => $this->comments->first()->user->avatar_url,
                ];
            }, null),
            'suggested' => VideoListResource::collection((new VideoService())->getSuggestedVideos($this->resource)),
        ];
    }
}
