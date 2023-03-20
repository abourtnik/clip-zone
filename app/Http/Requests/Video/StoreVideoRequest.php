<?php

namespace App\Http\Requests\Video;

use App\Enums\ImageType;
use App\Enums\VideoStatus;
use App\Enums\Languages;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

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
            'thumbnail' => [
                'file',
                'required',
                'mimetypes:'.implode(',', ImageType::acceptedMimeTypes()),
                'max:2048', // 2mo
                Rule::dimensions()->minWidth(1024)->minHeight(576)
            ],
            'status' => [
                'required',
                 Rule::in(VideoStatus::validStatus()),
            ],
            'scheduled_date' => [
                Rule::excludeIf($this->status != VideoStatus::PLANNED->value),
                'date',
                'after:now'
            ],
            'category_id' => 'nullable|exists:categories,id',
            'language' => [
                'nullable',
                new Enum(Languages::class)
            ],
            'allow_comments' => 'required|boolean',
            'default_comments_sort' => [
                'required',
                Rule::in(['top', 'newest']),
            ],
            'show_likes' => 'required|boolean',
            'playlists' => 'nullable|array',
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
