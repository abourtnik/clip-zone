<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Mail\Message;


class JobEventsSubscriber
{
    /**
     * Handle user have new subscriber events.
     */
    public function sendAdminEmail(JobFailed $event): void {

        $message = $event->exception->getMessage(). ' at ' . $event->exception->getFile(). ':' .$event->exception->getLine();

        Mail::raw($message, function (Message $message) use ($event) {
            $message->to(config('mail.server_mail'))->subject($event->job->resolveName(). ' - FAILED');
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
            JobFailed::class => 'sendAdminEmail',
        ];
    }
}
