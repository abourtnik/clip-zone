<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class NotificationResource extends JsonResource
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
            'ID' => $this->id,
            'message' => $this->message,
            'url' => route('user.notifications.click', $this),
            'is_read' => $this->is_read,
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
