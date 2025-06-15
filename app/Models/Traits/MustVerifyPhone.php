<?php

namespace App\Models\Traits;

use App\Notifications\Account\VerifyPhone;
use Illuminate\Support\Str;
use Propaganistas\LaravelPhone\PhoneNumber;


trait MustVerifyPhone
{
    /**
     * Determine if the user has verified their phone number.
     *
     * @return bool
     */
    public function hasVerifiedPhone(): bool
    {
        return !is_null($this->phone_verified_at);
    }

    /**
     * Mark the given user's phone as verified.
     *
     * @return bool
     */
    public function markPhoneAsVerified() : bool
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
            'phone_confirmation_code' => null
        ])->save();
    }

    /**
     * Send the phone verification notification.
     *
     * @return void
     */
    public function sendPhoneVerificationNotification(): void
    {
        $this->update([
            'phone_confirmation_code' => str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT),
            'phone_verified_at' => null
        ]);

        $this->notify(new VerifyPhone);
    }

    /**
     * Get the phone number that should be used for verification.
     *
     * @return PhoneNumber|null
     */
    public function getPhone() : ?PhoneNumber
    {
        return $this->phone;
    }

    /**
     * Get the phone code that should be used for verification.
     *
     * @return string|null
     */
    public function getPhoneCodeVerification() : ?string
    {
        return $this->phone_confirmation_code;
    }

    /**
     * Get the phone number without prefix.
     *
     * @return string|null
     */
    public function getPhoneWithoutPrefix() : ?string
    {
        return Str::replace(' ', '', $this->getPhone()?->formatNational());
    }

    /**
     * Check if the user should be prompted for phone verification.
     *
     * @return bool
     */
    public function waitForPhoneCode(): bool
    {
        return !is_null($this->getPhoneCodeVerification());
    }

    public function jsonPhone(): false|string
    {
        return json_encode([
            'flag' => collect(config('phone.countries'))->firstWhere('code', $this->getPhone()?->getCountry())['flag'] ?? '🇫🇷',
            'prefix' => collect(config('phone.countries'))->firstWhere('code', $this->getPhone()?->getCountry())['prefix'] ?? '+33',
            'code' => $this->getPhone()?->getCountry() ?? 'FR',
        ], JSON_HEX_QUOT);
    }
}
