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
            'username' => [
                'sometimes',
                'required',
                'min:'.config('validation.user.username.min'),
                'max:'.config('validation.user.username.max'),
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
            'description' => [
                'sometimes',
                'nullable',
                'string',
                'max:'.config('validation.user.description.max'),
            ],
            'country' => [
                'sometimes',
                'nullable',
                Rule::in(Countries::getCountryCodes())
            ],
            'website' => [
                'sometimes',
                'nullable',
                'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
                'max:'.config('validation.user.website.max'),
            ],
            'show_subscribers' => 'sometimes|nullable|boolean'
        ];
    }
}
