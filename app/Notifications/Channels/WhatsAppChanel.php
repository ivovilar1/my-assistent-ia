<?php

namespace App\Notifications\Channels;

use AllowDynamicProperties;
use Illuminate\Notifications\Notification;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

#[AllowDynamicProperties] class WhatsAppChanel
{
    public function __construct()
    {
        $this->config = config('services.twilio');
    }

    /**
     * @throws TwilioException
     * @throws ConfigurationException
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toWhatsApp($notifiable);
        $to = $notifiable->routeNotificationFor('WhatsApp');
        $from = $this->config['from'];

        $twilio = new Client($this->config['account_sid'], $this->config['auth_token']);
        // é preciso verificar se a mensagem é texto livre ou se é por template ( o template gera contentSid)
        if ($message->contentSid) {
            return $twilio->messages->create(
                'whatsapp:' . $to,
                [
                    'from' => 'whatsapp:' . $from,
                    'contentSid' => $message->contentSid,
                    'contentVariables' => $message->variables
                ]
            );
        }
        return $twilio->messages->create(
            'whatsapp:' . $to,
            [
                'from' => 'whatsapp:' . $from,
                'body' => $message->content
            ]
        );
    }
}
