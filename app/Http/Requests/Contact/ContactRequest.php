<?php

namespace App\Http\Requests\Contact;

use App\Rules\SpamWords;
use App\Rules\SpamEmails;
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
                 new SpamEmails
            ],
            'message' => [
                'required',
                'string',
                'min:'.config('validation.contact.message.min'),
                'max:'.config('validation.contact.message.max'),
                new SpamWords
            ],
            'website' => Rule::in([null])
        ];
    }
}
