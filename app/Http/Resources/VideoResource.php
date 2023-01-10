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
            'thumbnail' => $this->thumbnail_url,
            'duration' => $this->duration,
            'views' => trans_choice('views', $this->views_count),
            'route' => $this->route,
            'user' => [
                'username' => $this->user->username,
                'avatar' => $this->user->avatar_url,
                'route' => $this->user->route
            ],
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
