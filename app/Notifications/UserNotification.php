<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use function Termwind\render;

class UserNotification extends Notification
{
    use Queueable;

    private string $model;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $model)
    {
        $this->model = $model;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [

        ];
    }

    public function toBroadcast($notifiable)
    {
        $id = uniqid();

        return new BroadcastMessage([
            'toast_id' => $id,
            'toast' => view('admin.partials.toast', ['id' => $id, 'model' => $this->model])->render(),
        ]);
    }
}
