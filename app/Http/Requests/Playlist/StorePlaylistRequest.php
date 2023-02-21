<?php

namespace App\Http\Requests\Playlist;

use App\Enums\PlaylistStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StorePlaylistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:5000',
            'status' => [
                'required',
                new Enum(PlaylistStatus::class)
            ],
            'videos' => 'required|array',
            'videos.*' => 'exists:videos,id'
        ];
    }
}
