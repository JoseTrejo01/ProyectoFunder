<?php

use App\Models\Bitacora;
use App\Models\TblMsUsuario;

if (!function_exists('EVENT_BITACORA')) {
    function EVENT_BITACORA($id_usuario, $id_objeto, $accion, $descripcion)
    {
        $usuario = TblMsUsuario::find($id_usuario);

        Bitacora::create([
            'Id_Usuario'     => $id_usuario,
            'Nombre_Usuario' => $usuario ? $usuario->Nombre_Usuario : 'Desconocido',
            'Id_Objeto'      => $id_objeto,
            'Fecha'          => now(),
            'Accion'         => $accion,
            'Descripcion'    => $descripcion
        ]);
    }
}
