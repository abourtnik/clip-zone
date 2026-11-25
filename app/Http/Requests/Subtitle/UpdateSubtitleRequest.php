<?php

namespace App\Http\Requests\Subtitle;

use App\Models\Subtitle;
use App\Models\Video;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
     * @return array<string, mixed>
     */
    public function rules() : array
    {
        /** @var Video $video */
        $video = $this->route('video');

        /** @var Subtitle $subtitle */
        $subtitle = $this->route('subtitle');

        return [
            'name' => [
                'required',
                'string',
                'max:'.config('validation.subtitle.name.max'),
            ],
            'language' => [
                'required',
                Rule::in(Video::AVAILABLE_LANGUAGES),
                Rule::when($this->willBeEnabled($subtitle), [
                    Rule::unique('subtitles', 'language')
                        ->where(fn (Builder $query) => $query
                            ->where('video_id', $video->id)
                            ->where('is_enabled', true)
                        )
                        ->ignore($subtitle)
                        ->withoutTrashed(),
                ]),
            ],
            'file' => [
                'sometimes',
                'file',
                'mimetypes:text/vtt,text/plain',
                'max:2048', // 2mo
            ],
            'is_enabled' => [
                'required',
                'boolean',
            ]
        ];
    }

    public function messages() : array
    {
        return [
            'language.unique' => __('This language is already used for another active subtitle'),
        ];
    }

    private function willBeEnabled(Subtitle $subtitle): bool
    {
        return $this->has('is_enabled')
            ? $this->boolean('is_enabled')
            : $subtitle->is_enabled;
    }
}
