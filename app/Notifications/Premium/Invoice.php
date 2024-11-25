<?php

namespace App\Notifications\Premium;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Mail\Attachment;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class Invoice extends Notification
{

    /**
     * The invoice transaction.
     *
     * @var Transaction
     */
    protected Transaction $transaction;

    /**
     * Create a new message instance.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the notification's channels.
     *
     * @param User $notifiable
     * @return array|string
     */
    public function via(User $notifiable): array|string
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param User $notifiable
     * @return MailMessage
     */
    public function toMail(User $notifiable) : MailMessage
    {
        return $this->buildMailMessage($notifiable);
    }

    /**
     * Get the reset email notification mail message for the given URL.
     *
     * @param User $notifiable
     * @return MailMessage
     */
    protected function buildMailMessage(User $notifiable) : MailMessage
    {
        return (new MailMessage)
            ->subject(Lang::get('Your ' .config('app.name'). ' invoice '))
            ->markdown('mails.premium.invoice', [
                'notifiable' => $notifiable,
                'transaction' => $this->transaction
            ])
            ->attach(Attachment::fromStorage($this->transaction->invoice_url), [
                'as' => $this->transaction->invoice_name,
                'mime' => 'application/pdf',
            ]);
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
            'message' => 'Your ' .config('app.name'). ' invoice is available',
            'url' => route('user.invoices.show', $this->transaction),
            'created_at' => now(),
            'read_at' => false
        ];
    }
}
