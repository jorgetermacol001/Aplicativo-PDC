<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Productos;

class EnviarOcNotificacion extends Notification
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
        return ['database', 'mail']; // Notificación en la base de datos y por correo electrónico
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
            'mensaje' => "La OC del producto '{$this->producto->nombre}' ha sido enviada por el administrador de compras.",
            'usuario_admin' => $this->producto->adminCompra->name,
            'fecha' => now(),
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
            ->subject('Orden de Compra Enviada')
            ->greeting('Hola, ' . $notifiable->name)
            ->line("La OC del producto '{$this->producto->nombre}' ha sido enviada.")
            ->line('Administrador responsable: ' . $this->producto->adminCompra->name)
            ->action('Ver Detalles', url('/productos/show/' . $this->producto->id)) // Cambia la URL según tu aplicación
            ->line('Gracias por utilizar nuestro sistema.');
    }
}
