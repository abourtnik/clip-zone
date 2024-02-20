<?php

namespace App\Listeners;

use App\Events\Export\ExportFail;
use App\Events\Export\ExportFinished;
use App\Notifications\Export\ExportError;
use App\Notifications\Export\ExportSuccess;
use Illuminate\Events\Dispatcher;

class ExportEventSubscriber
{
    /**
     * Handle the event.
     *
     * @param ExportFinished  $event
     * @return void
     */
    public function sendSuccessNotification(ExportFinished $event): void
    {
        $event->user->notify(new ExportSuccess($event->export));
    }

    /**
     * Handle the event.
     *
     * @param ExportFail $event
     * @return void
     */
    public function sendErrorNotification(ExportFail $event): void
    {
        $event->user->notify(new ExportError($event->export));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            ExportFinished::class => 'sendSuccessNotification',
            ExportFail::class => 'sendErrorNotification',
        ];
    }
}
