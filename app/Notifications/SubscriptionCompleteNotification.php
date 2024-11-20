<?php

namespace App\Notifications;

use App\Notifications\Channels\WhatsAppChanel;
use App\Notifications\Channels\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionCompleteNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected string $name)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [WhatsAppChanel::class];
    }
    public function toWhatsApp($notification)
    {
        return (new WhatsAppMessage)
            ->contentSid('HX6e9ff12af53f0e70e6427550941f9822')
            ->variables([
                '1' => $this->name,
                '2' => ''
            ]);
    }
}
