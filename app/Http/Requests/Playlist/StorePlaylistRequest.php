<?php

namespace App\Http\Requests\Playlist;

use App\Enums\PlaylistStatus;
use App\Models\Video;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Database\Query\Builder;

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
            'title' => [
                'required',
                'string',
                'max:'.config('validation.playlist.title.max')
            ],
            'description' => [
                'nullable',
                'string',
                'max:'.config('validation.playlist.description.max')
            ],
            'status' => [
                'required',
                new Enum(PlaylistStatus::class)
            ],
            'videos' => 'nullable|array',
            'videos.*' => [
                'numeric',
                Rule::exists('videos', 'id')->where(function (Builder $query){
                    return (new Video)->scopeActive($query)->orWhere('user_id', Auth::user()->id);
                })
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
