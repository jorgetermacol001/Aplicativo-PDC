<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Productos;

class NotificacionGenerica extends Notification
{
    use Queueable;

    protected $mensaje;
    protected $productoId;

    /**
     * Constructor de la notificación.
     *
     * @param string $mensaje
     * @param int $productoId
     */
    public function __construct($mensaje, $productoId)
    {
        $this->mensaje = $mensaje;
        $this->productoId = $productoId;
    }

    /**
     * Define los canales a través de los cuales se enviará la notificación.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail']; // Notificación en base de datos y correo
    }

    /**
     * Contenido de la notificación para la base de datos.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'mensaje' => $this->mensaje,
            'producto_id' => $this->productoId,
        ];
    }

    /**
     * Contenido de la notificación para el correo electrónico.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Notificación General')
            ->greeting('Hola, ' . $notifiable->name)
            ->line($this->mensaje)
            ->action('Ver Detalles', url('/productos/show/' . $this->productoId)) // Cambia esta URL según tu aplicación
            ->line('Gracias por usar nuestro sistema.');
    }
}
