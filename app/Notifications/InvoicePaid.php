<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Productos;

class InvoicePaid extends Notification implements ShouldQueue
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
        return ['database', 'mail']; // Enviar por base de datos y correo
    }

    /**
     * Contenido de la notificación para la base de datos.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $usuarioSolicitante = $this->producto->solicitante; // Relación 'solicitante'
        $nombreSolicitante = $usuarioSolicitante ? $usuarioSolicitante->name : 'N/A';

        return [
            'producto_id' => $this->producto->id,
            'nombre' => $this->producto->nombre,
            'usuario_solicitante' => $nombreSolicitante,
            'mensaje' => "El solicitante {$nombreSolicitante} ha creado una nueva solicitud de producto: {$this->producto->nombre}.",
            'usuario_solicitante_id' => $this->producto->usuario_solicitante_id,
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
        $usuarioSolicitante = $this->producto->solicitante;
        $nombreSolicitante = $usuarioSolicitante ? $usuarioSolicitante->name : 'N/A';

        return (new MailMessage)
            ->subject('Nueva Solicitud de Producto')
            ->greeting('Hola, ' . $notifiable->name)
            ->line("El solicitante {$nombreSolicitante} ha creado una nueva solicitud de producto.")
            ->line("Producto: {$this->producto->nombre}")
            ->action('Ver Detalles', url('/productos/show/' . $this->producto->id)) // Cambia la URL según tu aplicación
            ->line('Gracias por usar nuestro sistema.');
    }
}
