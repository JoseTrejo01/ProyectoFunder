<?php

use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;


// ESTE ARCHIVO ES PARA LA FUNCION QUE VA REGISTRAR TODO LAS INTERACCIONES DE LOS USARIOS
// LA FUNCION ES GLOBAL, SOLO NECESITA SER LLAMADA EN LOS CONTROLADORES EN QUE SE OCUPEN
if (!function_exists('EVENT_BITACORA')) {
    function EVENT_BITACORA($id_usuario, $id_objeto, $accion, $descripcion)
    {
        Bitacora::create([
            'Id_Usuario' => $id_usuario,
            'Id_Objeto' => $id_objeto,
            'Fecha' => now(),
            'Accion' => $accion,
            'Descripcion' => $descripcion
        ]);
    }
}
