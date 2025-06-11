<?php
// app/Notifications/CustomResetPasswordNotification.php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends Notification
{
    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'Correo_Electronico' => $notifiable->Correo_Electronico, // Asegúrate de pasar el campo correcto
        ], false));

        return (new MailMessage)
            ->subject('Restablecer Contraseña - ' . config('app.name'))
            ->greeting('¡Hola ' . $notifiable->Nombre_Usuario . '!')
            ->line('Estás recibiendo este correo porque recibimos una solicitud de restablecimiento de contraseña para tu cuenta.')
            ->action('Restablecer Contraseña', $url)
            ->line('Este enlace expirará en 60 minutos.')
            ->line('Si no solicitaste un restablecimiento de contraseña, puedes ignorar este correo.')
            ->salutation('Saludos, ' . config('app.name'));
    }
}
