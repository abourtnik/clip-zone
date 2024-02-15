<?php

namespace App\Http\Requests\Subtitle;

use App\Enums\SubtitleStatus;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateSubtitleRequest extends FormRequest
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
                Rule::unique('subtitles', 'name')
                    ->where(fn (Builder $query) => $query->where('video_id', $this->subtitle->video->id))->ignore($this->subtitle),
            ],
            'status' => [
                'required',
                new Enum(SubtitleStatus::class),
            ],
            'file' => [
                'nullable',
                'file',
                'mimetypes:text/vtt,text/plain',
                'max:2048', // 2mo
            ]
        ];
    }
}
