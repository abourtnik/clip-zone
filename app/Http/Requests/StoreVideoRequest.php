<?php

namespace App\Http\Requests;

use App\Enums\ImageType;
use App\Enums\VideoStatus;
use App\Enums\VideoType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;

class StoreVideoRequest extends FormRequest
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
            'file' => [
                'file',
                'required',
                'mimetypes:'.implode(',', VideoType::acceptedMimeTypes()),
                'max:15360' // 15mo
            ],
            'thumbnail' => [
                'file',
                'required',
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
