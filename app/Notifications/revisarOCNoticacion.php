<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Productos;

class revisarOCNoticacion extends Notification
{
    use Queueable;

    protected $producto;

    public function __construct($producto)
    {
        $this->producto = $producto;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // Notificación en base de datos y correo
    }

    public function toDatabase($notifiable)
    {
        return [
            'mensaje' => "Tu compra '" . $this->producto->nombre . "' ha sido revisada.",
            'compra_id' => $this->producto->id
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Compra Revisada')
            ->greeting('Hola, ' . $notifiable->name)
            ->line("Tu compra '" . $this->producto->nombre . "' ha sido revisada.")
            ->action('Ver Detalles', url('/productos/show/' . $this->producto->id)) // Cambia esta URL según tu aplicación
            ->line('Gracias por utilizar nuestro sistema.');
    }
}
