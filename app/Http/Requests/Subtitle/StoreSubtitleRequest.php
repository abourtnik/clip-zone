<?php

namespace App\Http\Requests\Subtitle;

use App\Enums\SubtitleStatus;
use App\Models\Video;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Database\Query\Builder;

class StoreSubtitleRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:'.config('validation.subtitle.name.max'),
                Rule::unique('subtitles', 'name')->where(fn (Builder $query) => $query->where('video_id', $this->video->id)),
            ],
            'status' => [
                'required',
                new Enum(SubtitleStatus::class)
            ],
            'language' => [
                'required',
                Rule::in(Video::AVAILABLE_LANGUAGES),
                Rule::unique('subtitles', 'language')->where(fn (Builder $query) => $query->where('video_id', $this->video->id)),
            ],
            'file' => [
                'required',
                'file',
                'mimetypes:text/vtt,text/plain',
                'max:2048', // 2mo
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
            'file.required' => 'Please provide a WebVTT file',
        ];
    }
}
