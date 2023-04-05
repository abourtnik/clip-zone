<?php

namespace App\Http\Requests\Video;

use App\Enums\VideoType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


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
                // file mimetype only available on first chunk other chunk mimetype are application/octet-stream
                Rule::when($this->get('resumableChunkNumber') == 1, 'mimetypes:'.implode(',', VideoType::acceptedMimeTypes())),
                'max:10240' // Chunk size 10mo
            ]
        ];
    }
}
