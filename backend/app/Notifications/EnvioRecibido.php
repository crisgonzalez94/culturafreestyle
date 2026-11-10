<?php

namespace App\Notifications;

use App\Models\Envio;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnvioRecibido extends Notification
{
    use Queueable;

    public function __construct(public Envio $envio) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $envio = $this->envio;

        return (new MailMessage)
            ->subject("Nuevo evento: {$envio->evento}")
            ->greeting('¡Llegó un evento nuevo!')
            ->line("**{$envio->evento}** (#{$envio->id})")
            ->line('Disciplinas: '.implode(', ', $envio->disciplinas))
            ->line('Fecha: '.$envio->fecha->format('d/m/Y').($envio->hora ? " a las {$envio->hora}" : ''))
            ->line("Lugar: {$envio->lugar}".($envio->ciudad ? ", {$envio->ciudad}" : ''))
            ->lineIf($envio->organiza !== null, "Organiza: {$envio->organiza}")
            ->lineIf($envio->link !== null, "Link: {$envio->link}")
            ->lineIf($envio->detalles !== null, "Detalles: {$envio->detalles}");
    }
}
