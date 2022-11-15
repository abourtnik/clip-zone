<?php

namespace App\Http\Requests;

use App\Enums\ThumbnailType;
use App\Enums\VideoStatus;
use App\Enums\VideoType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
        $videoMimeTypes = implode(',', VideoType::acceptedMimeTypes());
        $thumbnailMimeTypes = implode(',', ThumbnailType::acceptedMimeTypes());

        return [
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:5000',
            'file' => 'file|required|mimetypes:'.$videoMimeTypes.'|max:15360', // 15 Mo
            'mimetype' => 'required',
            'duration' => 'required',
            'thumbnail' => 'file|required|mimetypes:'.$thumbnailMimeTypes.'|max:5120', // 5 Mo',
            'status' => [new Enum(VideoStatus::class)],
            'publication_date' => 'nullable|date|after:now',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'mimetype' => $this->file('file')->getMimeType(),
            'duration' =>  floor((new \getID3())->analyze($this->file('file')->getRealPath())['playtime_seconds'])
        ]);

    }
}
