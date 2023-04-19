<?php

namespace App\Listeners;

use App\Events\ExportFinished;
use App\Notifications\UserNotification;

class SendExportFinishedNotification
{
    /**
     * Handle the event.
     *
     * @param ExportFinished  $event
     * @return void
     */
    public function handle(ExportFinished $event): void
    {
        $event->user->notify(new UserNotification(
            'Your '. $event->export->file .' export is ready !',
            route('admin.exports.download', $event->export)
        ));
    }
}
