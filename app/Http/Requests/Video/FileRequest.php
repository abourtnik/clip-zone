<?php

namespace App\Http\Requests\Video;

use App\Enums\VideoType;
use App\Helpers\Number;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
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
            'resumableTotalSize' => 'required|integer|max:'.config('plans.'.Auth::user()->plan.'.max_file_size'),
            'file' => [
                'required',
                'file',
                // file mimetype only available on first chunk other chunk mimetype are application/octet-stream
                Rule::when($this->get('resumableChunkNumber') == 1, 'mimetypes:'.implode(',', VideoType::acceptedMimeTypes())),
                'max:10240' // Chunk size 10mo
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
        $fileSize = Number::formatSizeUnits($this->get('resumableTotalSize'));
        $maxSize = Number::formatSizeUnits(config('plans.'.Auth::user()->plan.'.max_file_size'));

        return [
            'resumableTotalSize.max' => 'Your file file is too large ('.$fileSize.') The uploading file should not exceed ' .$maxSize,
        ];
    }
}
