<?php

namespace App\Listeners\Bitacora;

use Illuminate\Auth\Events\PasswordReset;

class LogPasswordReset
{
    public function handle(PasswordReset $event): void
    {
        $user = $event->user;
        EVENT_BITACORA(
            $user->Id_Usuario ?? null,
            'Usuarios',
            'Actualización',
            "Cambio de contraseña para {$user->Usuario}"
        );
    }
}
