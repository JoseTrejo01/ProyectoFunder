<?php

namespace App\Listeners\Bitacora;

use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    public function handle(Failed $event): void
    {
        $credUsuario = $event->credentials['Usuario'] ?? $event->credentials['email'] ?? '(desconocido)';
        // No hay Id_Usuario porque falló
        EVENT_BITACORA(
            null,
            'Autenticación',
            'Fallo',
            "Fallo de inicio de sesión para {$credUsuario}"
        );
    }
}
