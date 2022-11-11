<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
                'required',
                'max:255',
                Rule::unique('users', 'username')->ignore(Auth::user())
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore(Auth::user())
            ],
            'avatar' => 'nullable',
        ];
    }
}
