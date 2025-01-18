<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Productos;

class RevisadaNotificacion extends Notification
{
    use Queueable;

    protected $producto;

    public function __construct(Productos $producto)
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
            'producto_id' => $this->producto->id,
            'nombre' => $this->producto->nombre,
            'mensaje' => "El solicitante ha revisado la OC: {$this->producto->nombre}. Con el id: {$this->producto->id}",
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('OC Revisada')
            ->greeting('Hola, ' . $notifiable->name)
            ->line("El solicitante ha revisado la OC: {$this->producto->nombre} con el id: {$this->producto->id}.")
            ->action('Ver Detalles', url('/productos/show/' . $this->producto->id)) // Cambia esta URL según tu aplicación
            ->line('Gracias por utilizar nuestro sistema.');
    }
}
