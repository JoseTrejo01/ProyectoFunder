<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Messages\MailMessage;

class CredencialesUsuarioNuevo extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $password;

    public function __construct($usuario, $password)
    {
        $this->usuario = $usuario;
        $this->password = $password;
    }

    public function build()
    {
        $mail = (new MailMessage)
            ->subject('Tus credenciales de acceso - ' . config('app.name'))
            ->greeting('¡Hola ' . $this->usuario->Nombre_Usuario . '!')
            ->line('Tu cuenta ha sido creada exitosamente. Aquí tienes tus credenciales de acceso:')
            ->line('Usuario: ' . $this->usuario->Usuario)
            ->line('Correo: ' . $this->usuario->Correo_Electronico)
            ->line('Contraseña temporal: ' . $this->password)
            ->line('Por seguridad, deberás cambiar tu contraseña en tu primer ingreso.')
            ->salutation('¡Bienvenido!');
        return $this->html($mail->render());
    }
}
