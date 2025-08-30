<?php

namespace App\Http\Requests\Playlist;

use App\Enums\PlaylistStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdatePlaylistRequest extends FormRequest
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
        $isDeletable = $this->route('playlist')->is_deletable;

        return [
            'title' => [
                Rule::requiredIf($isDeletable),
                'string',
                'max:'.config('validation.playlist.title.max')
            ],
            'description' => [
                'nullable',
                'string',
                'max:'.config('validation.playlist.description.max')
            ],
            'status' => [
                Rule::requiredIf($isDeletable),
                Rule::prohibitedIf(!$isDeletable),
                new Enum(PlaylistStatus::class)
            ],
            'videos' => 'nullable|array',
            'videos.*' => [
                'numeric',
                Rule::exists('videos', 'id')
            ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'videos.required' => 'You can\'t create playlist without videos',
            'videos.*.exists' => 'Video in position :position not exists on our records',
            'videos.*.numeric' => 'Video in position :position must be type numeric',
        ];
    }
}
