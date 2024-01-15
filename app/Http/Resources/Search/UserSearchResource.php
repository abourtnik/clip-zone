<?php

namespace App\Http\Resources\Search;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class UserSearchResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request) : array
    {
        return [
            'value' => $this->id,
            'label' => $this->username
        ];
    }
}
