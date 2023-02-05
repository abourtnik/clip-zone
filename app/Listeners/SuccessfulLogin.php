<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;


class SuccessfulLogin
{
    /**
     * Handle the event.
     *
     * @param Login $event
     * @return void
     */
    public function handle(Login $event): void
    {
        $event->user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->getClientIp(),
        ]);
    }
}
