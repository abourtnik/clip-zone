<?php

namespace App\Notifications;

use App\Http\Resources\NotificationResource;
use App\Models\User;
use Illuminate\Notifications\Notification;
use App\Models\Notification as NotificationModel;
use Illuminate\Notifications\Messages\BroadcastMessage;


class UserNotification extends Notification
{
    private string $message;
    private string $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $message, string $url)
    {
        $this->message = $message;
        $this->url = $url;
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
            'message' => $this->message,
            'url' => $this->url
        ];
    }

    public function toBroadcast(User $notifiable): BroadcastMessage
    {
        $notification = NotificationModel::where('user_id', $notifiable->id)
            ->oldest()
            ->get()
            ->last();

        return (new BroadcastMessage(
            (new NotificationResource($notification))->resolve()
        ))->onConnection('sync');
    }
}
