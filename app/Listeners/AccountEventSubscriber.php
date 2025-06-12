<?php

namespace App\Listeners;

use App\Events\Account\EmailUpdated;
use App\Events\Account\PasswordUpdated;
use App\Notifications\Account\PasswordUpdate;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Str;

class AccountEventSubscriber
{
    /**
     * Handle user password updated events.
     */
    public function sendUserPasswordUpdatedNotification(PasswordUpdated $event): void
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
    public function sendUserEmailUpdatedNotification(EmailUpdated $event): void
    {
        $user = auth()->user();

        $user->update(['temporary_email' => $event->email]);

        $user->sendUpdatedEmailVerificationNotification();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            PasswordUpdated::class => 'sendUserPasswordUpdatedNotification',
            EmailUpdated::class => 'sendUserEmailUpdatedNotification',
        ];
    }
}
