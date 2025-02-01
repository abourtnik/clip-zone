<?php

namespace App\Rules;

use App\Services\SpamService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SpamWords implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $spamService = new SpamService();

        if ($spamService->checkIfSpam($value)) {
            $fail('Spam detected');
        }
    }
}
