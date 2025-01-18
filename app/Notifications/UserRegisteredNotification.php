<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserRegisteredNotification extends Notification
{
    protected $user;
    protected $password;

    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('¡Cuenta Registrada!')
            ->greeting('Hola ' . $this->user->name . '!')
            ->line('Tu cuenta ha sido creada exitosamente.')
            ->line('**Usuario:** ' . $this->user->email)
            ->line('**Contraseña:** ' . $this->password)
            ->line('Por favor, cambia tu contraseña después de iniciar sesión.')
            ->action('Iniciar Sesión', url('/login'))
            ->line('Gracias por usar nuestra aplicación!');
    }
}

?>