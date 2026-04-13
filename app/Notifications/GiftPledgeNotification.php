<?php

namespace App\Notifications;

use App\Models\GiftPledge;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GiftPledgeNotification extends Notification
{
    use Queueable;

    public function __construct(
        private GiftPledge $pledge,
        private string $cancelUrl,
        private string $eventTitle,
    ) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🎁 Presente confirmado: ' . $this->pledge->giftItem->name)
            ->view('emails.gift-pledge', [
                'pledge'     => $this->pledge,
                'cancelUrl'  => $this->cancelUrl,
                'eventTitle' => $this->eventTitle,
            ]);
    }
}
