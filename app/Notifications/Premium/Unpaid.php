<?php

namespace App\Notifications\Premium;

use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class Unpaid extends Notification
{

    /**
     * The invoice transaction.
     *
     * @var integer
     */
    protected int $amount;

    /**
     * Create a new message instance.
     *
     * @param integer $amount
     * @return void
     */
    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    /**
     * Get the notification's channels.
     *
     * @param User $notifiable
     * @return array|string
     */
    public function via(User $notifiable): array|string
    {
        return ['mail'];
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
            ->subject(Lang::get('Your last payment of '.null. ' to ' .config('app.name'). ' was unsuccessful '))
            ->markdown('mails.premium.unpaid', [
                'notifiable' => $notifiable,
                'url' => Auth::user()->billingPortalUrl(route('user.edit')),
                'amount' => $this->amount
            ]);
    }
}
