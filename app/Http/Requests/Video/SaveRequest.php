<?php

namespace App\Http\Requests\Video;

use App\Enums\VideoStatus;
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
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

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
                Rule::exists('videos', 'id')->where(function (Builder $query){
                    return $query->whereIn('status', [VideoStatus::PUBLIC, VideoStatus::UNLISTED])
                        ->orWhere(function($query) {
                            $query->where('status', VideoStatus::PLANNED)
                                ->where('scheduled_date', '<=', now());
                        });
                }),
            ],
            'playlists' => 'array',
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
            'video_id.exists' => 'This video is not exist or is private',
            'playlists.*.exists' => 'Playlist in position :position not exists on our records',
            'playlists.*.numeric' => 'Playlist in position :position must be type numeric',
        ];
    }
}
