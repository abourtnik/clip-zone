<?php

namespace App\Http\Requests;

use App\Enums\ThumbnailType;
use App\Enums\VideoStatus;
use Illuminate\Foundation\Http\FormRequest;
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
        $thumbnailMimeTypes = implode(',', ThumbnailType::acceptedMimeTypes());

        return [
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:5000',
            'thumbnail' => 'nullable|file|mimetypes:'.$thumbnailMimeTypes.'|max:5120', // 5 Mo',
            'status' => [new Enum(VideoStatus::class)],
            'publication_date' => 'nullable|date|after:today',
        ];
    }
}
