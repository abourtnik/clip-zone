<?php

namespace App\Http\Resources\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserListResource extends JsonResource
{

    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'slug' => $this->slug,
            'avatar' => $this->avatar_url,
            'route' => $this->route,
            'show_subscribers' => $this->show_subscribers,
            $this->mergeWhen($this->show_subscribers, [
                'subscribers' => $this->subscribers_count
            ]),
            'videos_count' => $this->whenCounted('videos'),
            'created_at' => $this->created_at,
        ];
    }
}
