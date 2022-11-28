<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'avatar' => $this->user->avatar_url,
                'route' => route('pages.user', $this->user)
            ],
            'video' => [
                'id' => $this->video->id
            ],
            'created_at' => $this->created_at->diffForHumans(),
            'is_updated' => $this->created_at->ne($this->updated_at),
            'model' => Comment::class,
            'likes_count' => $this->likes_count,
            'dislikes_count' => $this->dislikes_count,
            'liked_by_auth_user' => $this->liked_by_auth_user,
            'disliked_by_auth_user' => $this->disliked_by_auth_user,
            'replies' =>  CommentResource::collection($this->replies)
        ];
    }
}
