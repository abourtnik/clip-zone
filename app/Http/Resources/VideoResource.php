<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
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
            'title' => $this->title,
            'poster' => $this->poster_url,
            'duration' => $this->duration,
            'views' => trans_choice('views', $this->views_count),
            'route' => route('video.show', $this),
            'user' => [
                'username' => $this->user->username,
                'avatar' => $this->user->avatar_url,
                'route' => route('pages.user', $this->user)
            ],
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}