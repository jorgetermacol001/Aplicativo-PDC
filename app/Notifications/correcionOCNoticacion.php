<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Productos;

class correcionOCNoticacion extends Notification
{
    use Queueable;

    protected $producto;

    /**
     * CrearOcNotificacion constructor.
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
            'producto_id' => $this->producto->id,
            'nombre' => $this->producto->nombre,
            'mensaje' => "Ha sido mandado a corrección para el producto: {$this->producto->nombre}.",
            'usuario_admin' => $this->producto->adminCompra->name,
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
            ->subject('Corrección de Orden de Compra')
            ->greeting('Hola, ' . $notifiable->name)
            ->line("La orden de compra para el producto '{$this->producto->nombre}' ha sido enviada a corrección.")
            ->line('Por favor, revisa los detalles y realiza las modificaciones necesarias.')
            ->action('Ver Detalles', url('/productos/show/' . $this->producto->id)) // Cambia la URL según tu aplicación
            ->line('Gracias por tu atención.');
    }
}
