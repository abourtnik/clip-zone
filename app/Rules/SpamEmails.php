<?php

namespace App\Rules;

use App\Services\SpamService;
use Closure;
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

        $domain = explode('@', $value)[1] ?? '';

        foreach ($emails as $email) {
            if (str_contains(strtolower('@'.$domain),strtolower($email))) {
                $fail('Spam detected');
            }
        }
    }
}
