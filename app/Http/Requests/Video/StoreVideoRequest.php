<?php

namespace App\Http\Requests\Video;

use App\Enums\ImageType;
use App\Enums\VideoStatus;
use App\Enums\VideoType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Symfony\Component\Intl\Languages;

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
            ],
            'category_id' => 'nullable|exists:categories,id',
            'language' => [
                'nullable',
                Rule::in(Languages::getLanguageCodes())
            ],
            'allow_comments' => 'required|boolean',
            'default_comments_sort' => [
                'required',
                Rule::in(['top', 'newest']),
            ],
            'show_likes' => 'required|boolean'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'allow_comments' => $this->request->has('allow_comments'),
            'show_likes' => $this->request->has('show_likes'),
        ]);
    }
}
