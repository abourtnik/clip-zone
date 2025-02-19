<?php

namespace App\Rules;

use App\Services\SpamService;
use Closure;
use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\ValidationRule;

class SpamEmails implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $spamService = new SpamService();

        $emails = $spamService->getEmails();

        foreach ($emails as $email) {
            if (Str::contains($value, $email, true)){
                $fail('Spam detected');
            }
        }
    }
}
