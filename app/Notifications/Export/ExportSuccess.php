<?php

namespace App\Notifications\Export;

use App\Models\Export;
use App\Models\User;
use Illuminate\Notifications\Notification;

class ExportSuccess extends Notification
{
    private Export $export;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Export $export)
    {
        $this->export = $export;
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
            'message' => 'Your '. $this->export->file .' export is ready !',
            'url' => route('admin.exports.download', $this->export),
            'created_at' => now(),
            'is_read' => false
        ];
    }
}
