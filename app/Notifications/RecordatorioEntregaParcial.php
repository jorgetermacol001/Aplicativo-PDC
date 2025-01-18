<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RecordatorioEntregaParcial extends Notification implements ShouldQueue
{
    use Queueable;

    protected $producto;

    public function __construct($producto)
    {
        $this->producto = $producto;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Cambia 'mail' si necesitas otro canal
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Recordatorio: Producto en entrega parcial')
            ->line("El producto con ID {$this->producto->id} y nombre {$this->producto->nombre} tiene entrega parcial.")
            ->line('¿Ya recibiste la totalidad?')
            ->action('Ver producto', url("/productos/show/{$this->producto->id}"))
            ->line('Gracias por usar nuestro sistema.');
    }

    public function toArray($notifiable)
    {
        return [
            'id_producto' => $this->producto->id,
            'nombre_producto' => $this->producto->nombre,
            'mensaje' => "El producto {$this->producto->nombre} está en entrega parcial. ¿Ya recibiste la totalidad?",
        ];
    }
}
