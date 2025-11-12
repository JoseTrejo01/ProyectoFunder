<?php

namespace App\Listeners\Bitacora;

use Illuminate\Auth\Events\Lockout;

class LogLockout
{
    public function handle(Lockout $event): void
    {
        $credUsuario = request('Usuario') ?? request('email') ?? '(desconocido)';
        EVENT_BITACORA(
            null,
            'Autenticación',
            'Lockout',
            "Usuario/IP bloqueado temporalmente por intentos fallidos: {$credUsuario}"
        );
    }
}
