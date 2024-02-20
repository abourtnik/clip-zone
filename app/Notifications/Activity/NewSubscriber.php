<?php

namespace App\Notifications\Activity;

use App\Models\User;
use Illuminate\Notifications\Notification;

class NewSubscriber extends Notification
{
    private User $subscriber;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $subscriber)
    {
        $this->subscriber = $subscriber;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param User $notifiable
     * @return array
     */
    public function via(User $notifiable) : array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  User $notifiable
     * @return array
     */
    public function toArray(User $notifiable) : array
    {
        return [
            'message' => $this->subscriber->username. ' has subscribed to your channel !',
            'url' => route('user.show', $this->subscriber),
            'created_at' => now()->diffForHumans(),
        ];
    }

}
