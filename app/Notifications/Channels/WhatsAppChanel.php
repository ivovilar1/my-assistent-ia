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
        $messages = $this->splitMessage($message->content);
        $sends = [];
        foreach($messages as $part) {
            $twilio->messages->create(
                'whatsapp:' . $to,
                [
                    'from' => 'whatsapp:' . $from,
                    'body' => $part
                ]
            );
        }
        return $sends;
    }

    /**
     * Metodo para quebrar as mensagens para nao limitar a reposta do chatgpt
     * Exemplo: o tamanho da mensagem deu 3.200 caracteres, quebra em duas partes de 1.600
     * @param $message
     * @param int $maxLength
     * @return array
     */
    protected function splitMessage($message, int $maxLength = 1600): array
    {
        $parts = [];
        $lines = explode("\n", $message);
        $currentPart = '';
        foreach ($lines as $line) {
            if (mb_strlen($line) > $maxLength) {
                if (!empty($currentPart)) {
                    $parts[] = $currentPart;
                    $currentPart = '';
                }

                $words = explode(' ', $line);
                $tempLine = '';

                foreach ($words as $word) {
                    if (mb_strlen($tempLine . ' ' . $word) <= $maxLength) {
                        $tempLine .= (empty($tempLine) ? '' : ' ') . $word;
                    } else {
                        if (!empty($tempLine)) {
                            $parts[] = $tempLine;
                        }
                        if (mb_strlen($word) > $maxLength) {
                            $parts = array_merge($parts, str_split($word, $maxLength));
                        } else {
                            $tempLine = $word;
                        }
                    }
                }

                if (!empty($tempLine)) {
                    $currentPart = $tempLine;
                }

            } else {
                if (mb_strlen($currentPart . (!empty($currentPart) ? "\n" : '') . $line) > $maxLength) {
                    $parts[] = $currentPart;
                    $currentPart = $line;
                } else {
                    $currentPart .= (!empty($currentPart) ? "\n" : '') . $line;
                }
            }
        }
        if (!empty($currentPart)) {
            $parts[] = $currentPart;
        }
        return $parts;
    }
}
