<?php

namespace App\Listeners\Bitacora;

use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user; // App\Models\User
        EVENT_BITACORA(
            $user->Id_Usuario ?? null,
            'Autenticación',
            'Ingreso',
            "Inicio de sesión exitoso del usuario {$user->Usuario}"
        );
    }
}
