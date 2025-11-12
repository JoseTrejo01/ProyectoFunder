<?php

namespace App\Listeners\Bitacora;

use Illuminate\Auth\Events\Logout;

class LogLogout
{
    public function handle(Logout $event): void
    {
        $user = $event->user;
        EVENT_BITACORA(
            $user->Id_Usuario ?? null,
            'Autenticación',
            'Salida',
            "Cierre de sesión del usuario {$user->Usuario}"
        );
    }
}
