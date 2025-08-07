<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Rol;
use App\Models\Objeto;
use App\Models\RolesObjeto;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PermisoController extends Controller
{

public function showForm()
{
   
    // Registrar acceso a gestión de permisos en bitácora
    $objeto = Objeto::where('Objeto', 'Permisos')->first(); 
    if (!$objeto) {
        $objeto = Objeto::where('Objeto', 'Objetos')->first(); // Fallback
    }
    if ($objeto && Auth::check()) {
        EVENT_BITACORA(
            Auth::user()->Id_Usuario,
            $objeto->Id_Objeto,
            'Ingreso',
            'El usuario accedió a la gestión de permisos'
        );
    }

    $roles = Rol::with(['permisos.objeto'])->where('Estado', 'ACTIVO')->get();
    $objetos = Objeto::where('Estado', 'ACTIVO')->get();
    $rolesObjetos = RolesObjeto::with(['rol', 'objeto'])->get();

    return view('admin.asignar_permisos', compact('roles', 'objetos', 'rolesObjetos'));
}
    public function asignarPermisos(Request $request)
    {
     
        $request->validate([
            'Id_Rol' => 'required|integer|exists:tbl_ms_rol,Id_Rol',
            'Id_Objeto' => 'required|integer|exists:tbl_ms_objeto,Id_Objeto',
        ]);

        $rol = Rol::find($request->Id_Rol);
        $objeto = Objeto::find($request->Id_Objeto);

        $permisos = [
            'Permiso_Insercion' => $request->has('Permiso_Insercion') ? 1 : 0,
            'Permiso_Eliminacion' => $request->has('Permiso_Eliminacion') ? 1 : 0,
            'Permiso_Actualizacion' => $request->has('Permiso_Actualizacion') ? 1 : 0,
            'Permiso_Consultar' => $request->has('Permiso_Consultar') ? 1 : 0,
        ];

        RolesObjeto::updateOrCreate(
            [
                'Id_Rol' => $request->Id_Rol,
                'Id_Objeto' => $request->Id_Objeto,
            ],
            $permisos
        );

        // Registrar asignación de permisos en bitácora
        $objetoBitacora = Objeto::where('Objeto', 'Permisos')->first();
        if (!$objetoBitacora) {
            $objetoBitacora = Objeto::where('Objeto', 'Objetos')->first(); // Fallback
        }
        if ($objetoBitacora && Auth::check()) {
            $permisosActivos = collect($permisos)
                ->filter(function($valor) { return $valor == 1; })
                ->keys()
                ->map(function($permiso) {
                    // Usar abreviaciones para ahorrar espacio
                    $abreviaciones = [
                        'Permiso_Insercion' => 'INS',
                        'Permiso_Eliminacion' => 'DEL', 
                        'Permiso_Actualizacion' => 'UPD',
                        'Permiso_Consultar' => 'VIEW'
                    ];
                    return $abreviaciones[$permiso] ?? $permiso;
                })
                ->implode(',');

            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objetoBitacora->Id_Objeto,
                'Update',
                "Permisos rol '{$rol->Rol}' obj '{$objeto->Objeto}': {$permisosActivos}"
            );
        }

        return back()->with('success', 'Permisos asignados correctamente');
    }
}
