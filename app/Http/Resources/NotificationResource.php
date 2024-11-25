<?php

namespace App\Http\Resources;

use App\Models\Notification;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @mixin Notification
 */
class NotificationResource extends JsonResource
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
            'message' => $this->message,
            'url' => $this->url,
            'is_read' => $this->is_read,
            'created_at' => $this->created_at
        ];
    }
}
