<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Productos;

class AlmacenistaNotificacion extends Notification
{
    protected $producto;

    public function __construct(Productos $producto)
    {
        $this->producto = $producto;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nueva CP enviada')
            ->line("Se ha enviado una CP asociada al producto: {$this->producto->nombre}.")
            ->action('Ver detalles', route('productos.show', $this->producto->id))
            ->line('Por favor, revisa y gestiona esta CP.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'mensaje' => "Se ha enviado una CP asociada al producto: {$this->producto->nombre}.",
            'producto_id' => $this->producto->id,
            'url' => route('productos.show', $this->producto->id),
        ];
    }
}
