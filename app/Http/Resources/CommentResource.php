<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'class' => Comment::class,
            'content' => $this->content,
            'short_content' => $this->short_content,
            'is_long' => $this->is_long,
            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'avatar' => $this->user->avatar_url,
                'route' => route('pages.user', $this->user),
                'is_author' => $this->video->user->is($this->user)
            ],
            'video' => [
                'id' => $this->video->id
            ],
            'created_at' => $this->created_at->diffForHumans(),
            'is_updated' => $this->created_at->ne($this->updated_at),
            'model' => Comment::class,
            'likes_count' => $this->likes_count,
            'dislikes_count' => $this->dislikes_count,
            'liked_by_auth_user' => $this->liked_by_auth_user > 0,
            'disliked_by_auth_user' => $this->disliked_by_auth_user > 0,
            'can_delete' => Auth::user()?->can('delete', $this->resource) ?? false,
            'can_update' => Auth::user()?->can('update', $this->resource) ?? false,
            'can_report' => Auth::user()?->can('report', $this->resource) ?? false,
            'can_pin' => Auth::user()?->can('pin', $this->resource) ?? false,
            'replies' =>  CommentResource::collection($this->replies),
            'is_pinned' => $this->is_pinned,
            'is_reply' => $this->is_reply,
            'is_author_reply' => $this->when(!$this->is_reply, fn() => $this->author_replies > 0),
            'author' => $this->when(!$this->is_reply, function () {
                return [
                    'username' => $this->video->user->username,
                    'avatar' => $this->video->user->avatar_url,
                ];
            })
        ];
    }
}
