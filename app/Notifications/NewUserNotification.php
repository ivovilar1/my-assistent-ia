<?php

namespace App\Notifications;

use App\Notifications\Channels\WhatsAppChanel;
use App\Notifications\Channels\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserNotification extends Notification
{
    use Queueable;

    /**
     * CreateUser a new notification instance.
     */
    public function __construct(protected string $name, protected string $stripeLink)
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
            ->contentSid('HX9dbea2b6ac5fb521933653443c17720d')
            ->variables([
                '1' => $this->name,
                '2' => $this->stripeLink,
            ]);
    }
}
