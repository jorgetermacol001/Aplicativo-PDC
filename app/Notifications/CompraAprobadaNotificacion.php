<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Productos;

class CompraAprobadaNotificacion extends Notification
{
    use Queueable;

    protected $producto;

    /**
     * Constructor de la notificación.
     *
     * @param Productos $producto
     */
    public function __construct($producto)
    {
        $this->producto = $producto;
    }

    /**
     * Define los canales de notificación.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail']; // Base de datos y correo electrónico
    }

    /**
     * Define el formato de la notificación para la base de datos.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'mensaje' => "Tu compra '" . $this->producto->nombre . "' ha sido aprobada.",
            'compra_id' => $this->producto->id
        ];
    }

    /**
     * Define el formato de la notificación para correo.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Compra Aprobada')
            ->greeting('Hola, ' . $notifiable->name)
            ->line("Tu compra '" . $this->producto->nombre . "' ha sido aprobada.")
            ->action('Ver Compra', url('/productos/show/' . $this->producto->id)) // Cambia la URL según tu aplicación
            ->line('Gracias por usar nuestra aplicación.');
    }
}
