<?php

namespace App\Listeners;

use App\Events\Account\EmailUpdated;
use App\Events\Account\PasswordUpdated;
use App\Notifications\Account\PasswordUpdate;
use Illuminate\Support\Str;

class AccountEventSubscriber
{
    /**
     * Handle user password updated events.
     */
    public function handleUserPasswordUpdated(PasswordUpdated $event): void
    {
        $user = auth()->user();

        $user->update([
            'password' => $event->password
        ]);

        $user->setRememberToken(Str::random(60));

        $user->notify(new PasswordUpdate());
    }

    /**
     * Handle user email update events.
     */
    public function handleUserEmailUpdated(EmailUpdated $event): void
    {
        $user = auth()->user();

        $user->update(['temporary_email' => $event->email]);

        $user->sendUpdatedEmailVerificationNotification();
    }
}
