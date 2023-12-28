<?php

namespace App\Observers;

use App\Helpers\Image;
use App\Models\User;

class UserObserver
{
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

        Image::deleteIf($user->avatar, User::AVATAR_FOLDER);
        Image::deleteIf($user->banner, User::BANNER_FOLDER);
    }
}
