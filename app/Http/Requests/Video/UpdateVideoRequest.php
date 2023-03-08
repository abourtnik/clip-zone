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

class UpdateVideoRequest extends FormRequest
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
            'title' => 'required|string|max:10',
            'description' => 'nullable|string|max:5000',
            'thumbnail' => [
                'nullable',
                'file',
                'mimetypes:'.implode(',', ImageType::acceptedMimeTypes()),
                'max:5120' // 5mo
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'allow_comments' => $this->request->has('allow_comments'),
            'show_likes' => $this->request->has('show_likes'),
        ]);
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
