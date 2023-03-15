<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Notifications\Notification;

class DatabaseChannel extends IlluminateDatabaseChannel
{
    /**
     * Build an array payload for the DatabaseNotification Model.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     * @return array
     */
    protected function buildPayload($notifiable, Notification $notification) : array
    {
        $data = $this->getData($notifiable, $notification);

        return [
            'user_id' => $notifiable->id,
            'message' => $data['message'],
            'url' => $data['url'],
        ];
    }
}
