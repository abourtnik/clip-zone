<?php

namespace App\Http\Requests\Contact;

use App\Services\SpamService;
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
    public function rules(SpamService $spamService) : array
    {
        return [
            'name' => [
                'required',
                'min:'.config('validation.contact.name.min'),
                'max:'.config('validation.contact.name.max'),
            ],
            'email' => ['required', 'email'],
            'message' => [
                'required',
                'string',
                'min:'.config('validation.contact.message.min'),
                'max:'.config('validation.contact.message.max'),
                function ($attribute, $value, $fail) use ($spamService) {
                    if ($spamService->checkIfSpam($value)) {
                        $fail('Spam detected');
                    }
                },
            ],
            'website' => Rule::in([null])
        ];
    }
}
