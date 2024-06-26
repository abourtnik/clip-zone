<?php

namespace App\Http\Resources;

use App\Models\Interaction;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @mixin Interaction
 */
class InteractionsResource extends JsonResource
{
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
            'status' => $this->status,
            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'avatar' => $this->user->avatar_url,
                'route' => $this->user->route,
                'is_auth_subscribe' => $this->user->is_auth_subscribe
            ],
            'video' => [
                'id' => $this->likeable->id
            ],
            'perform_at' => $this->perform_at->diffForHumans(),
        ];
    }
}
