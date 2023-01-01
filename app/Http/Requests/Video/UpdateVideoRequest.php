<?php

namespace App\Http\Requests\Video;

use App\Enums\ImageType;
use App\Enums\VideoStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:5000',
            'thumbnail' => [
                'nullable',
                'file',
                'mimetypes:'.implode(',', ImageType::acceptedMimeTypes()),
                'max:5120' // 5mo
            ],
            'status' => [new Enum(VideoStatus::class)],
            'publication_date' => [
                Rule::excludeIf($this->status != VideoStatus::PLANNED->value),
                'date',
                'after:now'
            ]
        ];
    }
}
