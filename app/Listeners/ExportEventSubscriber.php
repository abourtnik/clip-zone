<?php

namespace App\Listeners;

use App\Events\Export\ExportFail;
use App\Events\Export\ExportFinished;
use App\Notifications\Export\ExportError;
use App\Notifications\Export\ExportSuccess;

class ExportEventSubscriber
{
    /**
     * Handle the event.
     *
     * @param ExportFinished  $event
     * @return void
     */
    public function handleExportSuccess(ExportFinished $event): void
    {
        $event->user->notify(new ExportSuccess($event->export));
    }

    /**
     * Handle the event.
     *
     * @param ExportFail $event
     * @return void
     */
    public function handleExportFail(ExportFail $event): void
    {
        $event->user->notify(new ExportError($event->export));
    }
}
