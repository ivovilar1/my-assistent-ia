<?php

namespace App\Notifications;

use App\Notifications\Channels\WhatsAppChanel;
use App\Notifications\Channels\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MenuNotification extends Notification
{
    use Queueable;
    // indentação intencional, pois na twilio reconhece espaços corridos e muda a formatação
    private string $message = "Aqui estão os comandos que você pode usar para aproveitar ao máximo o assistente:

*!menu* - Exibe essa lista com todas as opções de comandos.
*!agenda* - Mostra as próximas tarefas e agendamentos que você tem programados.
*!insights* - Gera insights sobre suas tarefas dos últimos dias, ajudando você a identificar padrões e oportunidades para melhorar sua produtividade.
*!atualizar* - Atualiza uma determinada tarefa

É só escolher o comando que precisa e eu cuido do resto ou me mandar qualquer coisa que eu te ajudo! 😊";

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
    public function toWhatsApp()
    {
        return (new WhatsAppMessage)
            ->content($this->message);
    }
}
