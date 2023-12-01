<?php

namespace App\Listeners;

use Illuminate\Console\Events\ScheduledTaskFailed;
use Illuminate\Support\Facades\Mail;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Mail\Message;

class BackgroundEventSubscriber
{
    /**
     * Handle user have new subscriber events.
     */
    public function sendJobFailedEmail(JobFailed $event): void {

        $message = $event->exception->getMessage(). ' at ' . $event->exception->getFile(). ':' .$event->exception->getLine();

        Mail::raw($message, function (Message $message) use ($event) {
            $message->to(config('mail.server_mail'))->subject($event->job->resolveName(). ' - FAILED');
        });
    }

    /**
     * Handle user have new subscriber events.
     */
    public function sendScheduledTaskFailedEmail(ScheduledTaskFailed $event): void {

        $message = $event->exception->getMessage(). ' at ' . $event->exception->getFile(). ':' .$event->exception->getLine();

        Mail::raw($message, function (Message $message) use ($event) {
            $message->to(config('mail.server_mail'))->subject($event->task->mutexName(). ' - FAILED');
        });
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            JobFailed::class => 'sendJobFailedEmail',
            ScheduledTaskFailed::class => 'sendScheduledTaskFailedEmail',
        ];
    }
}
