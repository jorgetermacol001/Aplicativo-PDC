<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Productos;

class PagoLiberadoNotificacion extends Notification
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
     * Define los canales de notificación.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail']; // Notificación almacenada en la base de datos y enviada por correo
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
            'producto_id' => $this->producto->id,
            'nombre' => $this->producto->nombre,
            'mensaje' => "El pago del producto '{$this->producto->nombre}' ha sido liberado por el aprobador.",
            'usuario_admin' => $this->producto->adminCompra->name,
            'fecha' => now(),
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
            ->subject('Pago Liberado para Producto')
            ->greeting('Hola, ' . $notifiable->name)
            ->line("El pago del producto '{$this->producto->nombre}' ha sido liberado por el aprobador.")
            ->action('Ver Detalles', url('/productos/show/' . $this->producto->id)) // Cambia esta URL según tu aplicación
            ->line('Gracias por usar nuestro sistema.');
    }
}
