<?php

namespace App\Http\Requests\Subtitle;

use App\Models\Subtitle;
use App\Models\Video;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ToggleSubtitleRequest extends FormRequest
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

    protected function prepareForValidation(): void
    {
        $this->merge(['language' => $this->route('subtitle')->language]);
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

        if ($subtitle->is_enabled) {
            return [];
        }

        return [
            'language' => [
                Rule::unique('subtitles', 'language')
                    ->where(fn (Builder $query) => $query
                        ->where('video_id', $video->id)
                        ->where('is_enabled', true)
                    )
                    ->ignore($subtitle)
                    ->withoutTrashed(),
            ],
        ];
    }

    public function messages() : array
    {
        return [
            'language.unique' => __('This language is already used for another active subtitle'),
        ];
    }
}
