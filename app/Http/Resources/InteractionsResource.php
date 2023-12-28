<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class InteractionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'avatar' => $this->user->avatar_url,
                'route' => $this->user->route,
                'is_subscribe' => $this->user->is_subscribe_to_current_user > 0
            ],
            'video' => [
                'id' => $this->likeable->id
            ],
            'perform_at' => $this->perform_at->diffForHumans(),
        ];
    }
}
