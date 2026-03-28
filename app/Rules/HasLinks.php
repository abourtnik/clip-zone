<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HasLinks implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (preg_match('/https?:\/\/[^\s]+/', $value)) {
            $fail(__('Links are not authorized in message'));
        }
    }
}
