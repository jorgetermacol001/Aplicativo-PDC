<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Productos;

class LiberarOcNotificacion extends Notification
{
    use Queueable;

    protected $producto;

    /**
     * Constructor de la notificación.
     *
     * @param Productos $producto
     */
    public function __construct(Productos $producto)
    {
        $this->producto = $producto;
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
            'producto_id' => $this->producto->id,
            'nombre' => $this->producto->nombre,
            'mensaje' => "La OC con el nombre {$this->producto->nombre} ha sido liberada.",
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
            ->subject('OC Liberada')
            ->greeting('Hola, ' . $notifiable->name)
            ->line("La OC con el nombre '{$this->producto->nombre}' ha sido liberada.")
            ->action('Ver Detalles', url('/productos/show/' . $this->producto->id)) // Cambia esta URL según tu aplicación
            ->line('Gracias por usar nuestro sistema.');
    }
}
