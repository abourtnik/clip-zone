<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request  $request
     * @return array
     */
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'is_private' => $this->user->isNot(Auth::user()) && !$this->is_public,
            $this->mergeUnless($this->user->isNot(Auth::user()) && !$this->is_public, [
                'title' => $this->title,
                'short_title' => $this->short_title,
                'thumbnail' => $this->thumbnail_url,
                'duration' => $this->duration,
                'views' => trans_choice('views', $this->views_count),
                'route' => $this->route,
                'user' => [
                    'username' => $this->user->username,
                    'avatar' => $this->user->avatar_url,
                    'route' => $this->user->route
                ],
                'publication_date' => $this->publication_date->diffForHumans(),
            ])
        ];
    }
}
