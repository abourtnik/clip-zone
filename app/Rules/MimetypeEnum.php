<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class MimetypeEnum implements ValidationRule
{
    public string $enumClass;

    public function __construct(string $enumClass)
    {
        $this->enumClass = $enumClass;
    }
    /**
     * Run the validation rule.
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var UploadedFile $value */

        $validator = Validator::make(
            [$attribute => $value],
            [$attribute => 'mimetypes:'.$this->enumClass::toString()]
        );

        if ($validator->fails()) {
            $fail(__('Invalid :attribute format'));
        }
    }
}
