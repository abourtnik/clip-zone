<?php

namespace App\Http\Requests\Video;

use App\Enums\VideoStatus;
use App\Playlists\Types\LikedVideosPlaylist;
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
                        ->orWhere(function(Builder $query) {
                            $query->where('status', VideoStatus::PLANNED)
                                ->where('scheduled_at', '<=', now());
                        })->orWhere('user_id', Auth::user()->id);
                }),
            ],
            'playlist_id' => [
                'required',
                Rule::exists('playlists', 'id')->where(function (Builder $query){
                    return $query
                        ->where('user_id', $this->user()->id)
                        ->whereNot('title', LikedVideosPlaylist::getName());
                })
            ],
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
            'playlist_id.exists' => 'This playlist is not exist or is private',
        ];
    }
}
