<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Productos;

class NotificacionNuevaProducto extends Notification
{
    use Queueable;
    
    protected $producto;

    // Constructor donde recibimos el producto
    public function __construct(Productos $producto)
    {
        $this->producto = $producto;
    }

    // Especificamos que la notificación será enviada por base de datos y correo
    public function via($notifiable)
    {
        return ['database', 'mail']; // Usamos base de datos y correo para la notificación
    }

    // Generamos el contenido dinámico para la notificación en la base de datos
    public function toDatabase($notifiable)
    {
        // Creamos el contenido dinámico basado en el producto
        return [
            'producto_id' => $this->producto->id,
            'nombre' => $this->producto->nombre,
            'usuario_solicitante' => $this->producto->usuario_solicitante->name, // Nombre del solicitante
            'mensaje' => "El solicitante {$this->producto->usuario_solicitante->name} ha creado una nueva solicitud de producto: {$this->producto->nombre}.",
        ];
    }

    // Contenido de la notificación para el correo electrónico
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nueva Solicitud de Producto')
            ->greeting('Hola, ' . $notifiable->name)
            ->line("El solicitante {$this->producto->usuario_solicitante->name} ha creado una nueva solicitud de producto: {$this->producto->nombre}.")
            ->action('Ver Detalles', url('/productos/show/' . $this->producto->id)) // Cambia esta URL según tu aplicación
            ->line('Gracias por usar nuestro sistema.');
    }
}
