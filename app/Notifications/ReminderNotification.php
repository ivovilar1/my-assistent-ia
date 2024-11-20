<?php

namespace App\Notifications;

use App\Notifications\Channels\WhatsAppChanel;
use App\Notifications\Channels\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReminderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
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
            ->contentSid('HXa1d9f3f5f93db059b96265bb96831ebb')
            ->variables([]);
    }
}
