<?php

namespace App\Models\Traits;

use App\Models\User;
use App\Notifications\Account\VerifyUpdatedEmail;

trait MustVerifyUpdatedEmail
{
    public function sendUpdatedEmailVerificationNotification(): void
    {
        $user = $this;

        // Create temp user with the temporary email for send email to temporary email
        $tempUser = new User([ ...$user->toArray(), 'email' => $user->getTemporaryEmailForVerification() ]);
        $tempUser->id = $user->id;

        $tempUser->notify(new VerifyUpdatedEmail());
    }

    public function getTemporaryEmailForVerification(): ?string
    {
        return $this->temporary_email;
    }
}
