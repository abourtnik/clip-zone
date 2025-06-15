<?php

namespace App\Http\Requests;

use App\Enums\ImageType;
use App\Rules\MimetypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\Rules\Phone;
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

    public function prepareForValidation(): void
    {
        if (null !== $this->get('phone')) {
            $this->merge([
                'phone' => $this->get('prefix') . $this->get('phone')
            ]);
        }
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
            'slug' => [
                'sometimes',
                'required',
                'regex:/^[a-zA-Z0-9._-]+$/',
                'min:'.config('validation.user.slug.min'),
                'max:'.config('validation.user.slug.max'),
                Rule::unique('users', 'slug')->ignore(Auth::user())
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
                new MimetypeEnum(ImageType::class),
                'max:2048', // 2mo
                Rule::dimensions()->minWidth(98)->minHeight(98)
            ],
            'phone' => [
                'sometimes',
                'nullable',
                'string',
                (new Phone)->countryField('code')->type('mobile')
            ],
            'code' => [
                'sometimes',
                'nullable',
                'string',
                Rule::in(array_column(config('phone.countries'), 'code'))
            ],
            'prefix' => [
                'sometimes',
                'nullable',
                'string',
                Rule::in(array_column(config('phone.countries'), 'prefix'))
            ],
            'banner' => [
                'sometimes',
                'nullable',
                'file',
                new MimetypeEnum(ImageType::class),
                'max:2048', // 2mo
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
                'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w@ \.-]*)*\/?$/',
                'max:'.config('validation.user.website.max'),
            ],
            'show_subscribers' => 'sometimes|nullable|boolean'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() : array
    {
        return [
            'avatar.max' => 'The avatar file is too large. Its size should not exceed 2 MB.',
            'avatar.dimensions' => 'The avatar image must be at least :min_width x :min_height pixels',
            'banner.max' => 'The banner file is too large. Its size should not exceed 2 MB.',
            'banner.dimensions' => 'The banner image must be at least :min_width x :min_height pixels',
            'slug.regex' => ':attribute can only contain letters, numbers, periods (.), dashes (-), and underscores (_)'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'slug' => 'Handle',
        ];
    }
}
