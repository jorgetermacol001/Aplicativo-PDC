<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Productos;

class CrearOcNotificacion extends Notification
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
            'mensaje' => "Se ha creado la OC para el producto: {$this->producto->nombre}.",
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
            ->subject('Nueva Orden de Compra Creada')
            ->greeting('Hola, ' . $notifiable->name)
            ->line("Se ha creado una nueva Orden de Compra para el producto '{$this->producto->nombre}'.")
            ->line('Administrador responsable: ' . $this->producto->adminCompra->name)
            ->action('Ver Detalles', url('/productos/' . $this->producto->id)) // Cambia la URL según tu aplicación
            ->line('Gracias por utilizar nuestro sistema.');
    }
}
