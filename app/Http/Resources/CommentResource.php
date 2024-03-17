<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * @mixin Comment
 */
class CommentResource extends JsonResource
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
            'class' => Comment::class,
            'content' => $this->content,
            'parsed_content' => $this->parsed_content,
            'short_content' => $this->short_content,
            'is_long' => $this->is_long,
            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'avatar' => $this->user->avatar_url,
                'route' => $this->user->route,
                'is_author' => $this->video->user->is($this->user)
            ],
            'video' => [
                'id' => $this->video->id
            ],
            'created_at' => $this->created_at->diffForHumans(),
            'is_updated' => $this->is_updated,
            'likes_count' => $this->likes_count,
            'dislikes_count' => $this->dislikes_count,
            'liked_by_auth_user' => $this->resource->liked_by_auth_user,
            'disliked_by_auth_user' => $this->resource->disliked_by_auth_user,
            'can_delete' => Auth::user()?->can('delete', $this->resource) ?? false,
            'can_update' => Auth::user()?->can('update', $this->resource) ?? false,
            'can_report' => Auth::user()?->can('report', $this->resource) ?? false,
            'can_pin' => Auth::user()?->can('pin', $this->resource) ?? false,
            'replies' => $this->when($this->resource->total_replies > 0, function () {
                return [
                    'data' => CommentResource::collection($this->replies),
                    'links' => [
                        'next' => $this->replies->count() < $this->resource->total_replies ? route('comments.replies', ['comment' => $this->resource , 'page' => 2]) : null,
                    ],
                    'meta' => [
                        'total' => $this->resource->total_replies
                    ]
                ];
            }),
            'is_pinned' => $this->is_pinned,
            'is_reply' => $this->is_reply,
            'is_author_reply' => $this->when(!$this->is_reply, fn() => $this->resource->is_author_reply),
            'author' => $this->when(!$this->is_reply, function () {
                return [
                    'username' => $this->video->user->username,
                    'avatar' => $this->video->user->avatar_url,
                ];
            }),
            'reported_at' => $this->when($this->resource->reportByAuthUser, fn() => $this->reportByAuthUser->created_at->diffForHumans()),
        ];
    }
}
