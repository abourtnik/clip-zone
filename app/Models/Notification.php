<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Notifications\DatabaseNotification;

/**
 * @mixin IdeHelperNotification
 */
class Notification extends DatabaseNotification
{
    protected $guarded = ['id'];

    /**
     * Get the notifiable entity that the notification belongs to.
     *
     * @return MorphTo
     */
    public function user() : MorphTo
    {
        return $this->notifiable();
    }


    /**
     * -------------------- ATTRIBUTES --------------------
     */

    public function isRead() : Attribute {
        return Attribute::make(
            get: fn () => $this->read()
        );
    }

    public function message() : Attribute {
        return Attribute::make(
            get: fn () => $this->data['message']
        );
    }

    public function url() : Attribute {
        return Attribute::make(
            get: fn () => $this->data['url']
        );
    }
}
