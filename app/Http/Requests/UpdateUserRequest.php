<?php

namespace App\Http\Requests;

use App\Enums\ImageType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\Intl\Countries;

class UpdateUserRequest extends FormRequest
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
            'username' => [
                'sometimes',
                'required',
                'max:255',
                Rule::unique('users', 'username')->ignore(Auth::user())
            ],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore(Auth::user())
            ],
            'avatar' => [
                'sometimes',
                'nullable',
                'file',
                'mimetypes:'.implode(',', ImageType::acceptedMimeTypes()),
                'max:5120' // 5mo
            ],
            'banner' => [
                'sometimes',
                'nullable',
                'file',
                'mimetypes:'.implode(',', ImageType::acceptedMimeTypes()),
                'max:5120' // 5mo
            ],
            'description' => 'sometimes|nullable|string|max:5000',
            'country' => [
                'sometimes',
                'nullable',
                Rule::in(Countries::getCountryCodes())
            ],
            'website' => 'sometimes|nullable|url|max:255',
            'show_subscribers' => 'sometimes|boolean'
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
            'show_subscribers' => $this->request->has('show_subscribers')
        ]);
    }
}
