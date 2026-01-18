<?php

namespace App\Http\Requests\Video;

use App\Enums\ImageType;
use App\Enums\VideoStatus;
use App\Models\Video;
use App\Rules\MimetypeEnum;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreVideoRequest extends FormRequest
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
        /** @var Video $video */
        $video = $this->route('video');

        return [
            'title' => [
                'required',
                'string',
                'max:'.config('validation.video.title.max')
            ],
            'description' => [
                'nullable',
                'string',
                'max:'.config('validation.video.description.max')
            ],
            'thumbnail_file' => [
                'sometimes',
                'file',
                new MimetypeEnum(ImageType::class),
                'max:2048', // 2mo
            ],
            'thumbnail' => [
                'required',
                'numeric',
                'min:0',
                Rule::when($this->get('thumbnail'), Rule::exists('thumbnails', 'id')->where(function (Builder $query) use ($video) {
                    return $query->where('video_id', $video->id);
                }))
            ],
            'status' => [
                'required',
                 Rule::in(VideoStatus::validStatus()),
            ],
            'scheduled_at' => [
                Rule::excludeIf($this->status != VideoStatus::PLANNED->value),
                'required',
                'date',
                'after:now'
            ],
            'category_id' => 'nullable|exists:categories,id',
            'language' => [
                'nullable',
                Rule::in(Video::AVAILABLE_LANGUAGES),
            ],
            'allow_comments' => 'required|boolean',
            'default_comments_sort' => [
                'required',
                Rule::in(['top', 'newest']),
            ],
            'show_likes' => 'required|boolean',
            'show_ad' => 'required|boolean',
            'playlists' => 'nullable|array',
            'playlists.*' => [
                'numeric',
                Rule::exists('playlists', 'id')->where(function (Builder $query){
                    return $query
                        ->where('user_id', Auth::user()->id)
                        ->where('is_deletable', true);
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
