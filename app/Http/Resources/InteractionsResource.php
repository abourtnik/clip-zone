<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InteractionsResource extends JsonResource
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
            'status' => $this->status,
            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'avatar' => $this->user->avatar_url,
                'route' => route('pages.user', $this->user),
                'is_subscribe' => auth()->user()->isSubscribe($this->user)
            ],
            'video' => [
                'id' => $this->likeable->id
            ],
            'perform_at' => $this->perform_at->diffForHumans()
        ];
    }
}
