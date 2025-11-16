<?php

use App\Models\Bitacora;
use App\Models\TblMsUsuario;
use App\Models\Objeto;
use Illuminate\Support\Facades\Auth;

/**
 * Registra un evento en la bitácora con deduplicación por CACHE.
 *
 * Dedup: evita repetir el MISMO evento (usuario+objeto+acción+descripción)
 * durante N segundos, sin depender de la hora de BD.
 *
 * Configura la ventana en .env: BITACORA_DEDUP_SECONDS=3 (0 para desactivar)
 */
if (!function_exists('EVENT_BITACORA')) {
    function EVENT_BITACORA($id_usuario, $id_objeto, $accion, $descripcion)
    {
        try {
            // 0) Ventana anti-duplicado (seg)
            $VENTANA_SEG = (int) (config('bitacora.dedup_seconds') ?? env('BITACORA_DEDUP_SECONDS', 3));
            if ($VENTANA_SEG < 0) $VENTANA_SEG = 0;

            // 1) Nombre del usuario
            $nombreUsuario = null;
            if ($id_usuario) {
                $nombreUsuario = optional(TblMsUsuario::find($id_usuario))->Nombre_Usuario;
            }
            if (!$nombreUsuario && Auth::check()) {
                $nombreUsuario = optional(Auth::user())->Nombre_Usuario;
            }
            $nombreUsuario = $nombreUsuario ?: 'Desconocido';

            // 2) Resolver Id_Objeto (acepta id o nombre)
            if (is_numeric($id_objeto)) {
                $idObjeto = (int) $id_objeto;
            } else {
                // Soporta acentos comunes (Autenticación/Autenticacion)
                $idObjeto = optional(
                    Objeto::where('Objeto', $id_objeto)->first()
                    ?? Objeto::where('Objeto', str_replace('ó','o',str_replace('í','i',$id_objeto)))->first()
                )->Id_Objeto;
            }

            // 3) Descripción simple (sin IP/UA)
            $descFinal = is_array($descripcion)
                ? json_encode($descripcion, JSON_UNESCAPED_UNICODE)
                : (string) $descripcion;

            // 4) DEDUP por CACHE (independiente de la BD/zonas horarias)
            if ($VENTANA_SEG > 0) {
                $key = 'bitacora:dedup:' .
                       ($id_usuario ?? 'null') . ':' .
                       ($idObjeto ?: 'null') . ':' .
                       $accion . ':' .
                       md5($descFinal);

                // cache()->add devuelve false si YA existe la clave
                if (!cache()->add($key, 1, $VENTANA_SEG)) {
                    return; // Evita duplicado inmediato
                }
            }

            // 5) Insertar
            Bitacora::create([
                'Id_Usuario'     => $id_usuario,
                'Nombre_Usuario' => $nombreUsuario,
                'Id_Objeto'      => $idObjeto ?: null,
                'Fecha'          => now(),
                'Accion'         => $accion,
                'Descripcion'    => $descFinal,
            ]);
        } catch (\Throwable $e) {
            logger()->warning('Bitácora falló: '.$e->getMessage());
        }
    }
}
