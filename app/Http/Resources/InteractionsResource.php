<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserListResource;
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
            'user' => UserListResource::make($this->user),
            'perform_at' => $this->perform_at,
        ];
    }
}
