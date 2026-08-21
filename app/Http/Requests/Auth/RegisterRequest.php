<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    private const int MAX_ATTEMPTS = 3;
    private const int DELAY = 3600; // delay in seconds
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
                'required',
                'min:'.config('validation.user.username.min'),
                'max:'.config('validation.user.username.max'),
                'unique:users,username'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(config('validation.user.password.min'))
            ],
            'cgu' => ['accepted']
        ];
    }

    protected function passedValidation() :void
    {
        $key = $this->throttleKey();

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {

            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'throttle' => __('throttle.register', ['seconds' => $seconds])
            ]);
        }

        RateLimiter::increment($key, self::DELAY);
    }

    protected function throttleKey(): string
    {
        return 'register' . '|' . $this->ip();
    }
}
