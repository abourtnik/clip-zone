<?php

namespace App\Http\Requests\Video;

use App\Enums\VideoType;
use App\Rules\MimetypeEnum;
use Illuminate\Support\Number;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Closure;

class FileRequest extends FormRequest
{
    public const CHUNK_SIZE = 10_240; // 10 MB


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
            'resumableFilename' => [
                'required',
                function (string $attribute, string $value, Closure $fail) {
                    if (mb_strlen(pathinfo($value, PATHINFO_FILENAME), 'UTF-8') > 100) {
                        $fail("Your file name does not exceed 100 characters");
                    }
                },
            ],
            'resumableTotalSize' => 'required|integer|max:'.config('plans.'.Auth::user()->plan.'.max_file_size'),
            'file' => [
                'required',
                'file',
                // file mimetype only available on first chunk other chunk mimetype are application/octet-stream
                Rule::when($this->get('resumableChunkNumber') == 1, [new MimetypeEnum(VideoType::class)]),
                'max:'.self::CHUNK_SIZE // Chunk size 10mo
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
        $fileSize = Number::fileSize($this->get('resumableTotalSize'));
        $maxSize = Number::fileSize(config('plans.'.Auth::user()->plan.'.max_file_size'));

        return [
            'resumableTotalSize.max' => 'Your file file is too large ('.$fileSize.') The uploading file should not exceed ' .$maxSize,
            'resumableFilename.max' => 'Your file name does not exceed 100 characters',
        ];
    }
}
