<?php

namespace App\Http\Requests\Contact;

use App\Rules\HasLinks;
use App\Rules\SpamWords;
use App\Rules\SpamEmails;
use App\Rules\Turnstile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
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
     * @return array
     */
    public function rules() : array
    {
        return [
            'name' => [
                'required',
                'min:'.config('validation.contact.name.min'),
                'max:'.config('validation.contact.name.max'),
            ],
            'email' => [
                'bail',
                'required',
                'email',
                 //new SpamEmails
            ],
            'message' => [
                'bail',
                'required',
                'string',
                'min:'.config('validation.contact.message.min'),
                'max:'.config('validation.contact.message.max'),
                //new SpamWords,
                //new HasLinks
            ],
            'website' => Rule::in([null]),
            'cf-turnstile-response' => [
                config('services.turnstile.enabled') ? 'required' : 'nullable',
                new Turnstile
            ]
        ];
    }

    public function messages() : array
    {
        return [
            'cf-turnstile-response.required' => __('Please complete the anti-bot verification'),
        ];
    }
}
