<?php

namespace App\Http\Requests\Video;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SaveRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules() : array
    {
        return [
            'video_id' => [
                'required',
                'exists:videos,id'
            ],
            'playlists' => 'required|array',
            'playlists.*' => [
                'numeric',
                Rule::exists('playlists', 'id')->where(function (Builder $query){
                    return $query->where('user_id', Auth::user()->id);
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
            'playlists.*.exists' => 'Playlist in position :position not exists on our records',
            'playlists.*.numeric' => 'Playlist in position :position must be type numeric',
        ];
    }
}
