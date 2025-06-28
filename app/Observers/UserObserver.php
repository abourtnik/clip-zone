<?php

namespace App\Observers;

use App\Helpers\File;
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


    /**
     * Handle the User "deleting" event.
     *
     * @param User $user
     * @return void
     */
    public function deleting(User $user) : void
    {
        $user->videos_comments_interactions()->delete();
        $user->videos_interactions()->delete();

        File::deleteIfExist($user->avatar, User::AVATAR_FOLDER);
        File::deleteIfExist($user->banner, User::BANNER_FOLDER);
    }
}
