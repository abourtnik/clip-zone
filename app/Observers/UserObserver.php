<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "updated" event.
     *
     * @param User $user
     * @return void
     */
    public function updated(User $user) : void
    {
        if ($user->hasStripeId()) {
            $user->syncStripeCustomerDetails();
        }
    }
}
