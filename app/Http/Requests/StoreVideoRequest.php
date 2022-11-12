<?php

namespace App\Http\Requests;

use App\Enums\VideoStatus;
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
        return [
            'title' => 'required|max:255',
            'description' => 'nullable',
            'file' => 'required',
            'poster' => 'required',
            'status' => ['required', new Enum(VideoStatus::class)],
            'publication_date' => 'nullable',
        ];
    }
}
