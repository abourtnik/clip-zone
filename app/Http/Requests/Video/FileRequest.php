<?php

namespace App\Http\Requests\Video;

use App\Enums\VideoType;
use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
            'file' => [
                'file',
                'required',
                'mimetypes:'.implode(',', VideoType::acceptedMimeTypes()),
                'max:15360' // 15mo
            ],
            'duration' => 'required|numeric'
        ];
    }
}
