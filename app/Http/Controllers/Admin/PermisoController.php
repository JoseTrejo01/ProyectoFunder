<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Rol;
use App\Models\Objeto;
use App\Models\RolesObjeto;
use App\Http\Controllers\Controller;

class PermisoController extends Controller
{

public function showForm()
{
    $roles = Rol::with(['permisos.objeto'])->get();
    $objetos = Objeto::all();
    $rolesObjetos = RolesObjeto::with(['rol', 'objeto'])->get();

    return view('admin.asignar_permisos', compact('roles', 'objetos', 'rolesObjetos'));
}
    public function asignarPermisos(Request $request)
    {
        $request->validate([
            'Id_Rol' => 'required|integer|exists:tbl_ms_rol,Id_Rol',
            'Id_Objeto' => 'required|integer|exists:tbl_ms_objeto,Id_Objeto',
        ]);

        RolesObjeto::updateOrCreate(
            [
                'Id_Rol' => $request->Id_Rol,
                'Id_Objeto' => $request->Id_Objeto,
            ],
            [
                'Permiso_Insercion' => $request->has('Permiso_Insercion') ? 1 : 0,
                'Permiso_Eliminacion' => $request->has('Permiso_Eliminacion') ? 1 : 0,
                'Permiso_Actualizacion' => $request->has('Permiso_Actualizacion') ? 1 : 0,
                'Permiso_Consultar' => $request->has('Permiso_Consultar') ? 1 : 0,
            ]
        );

        return back()->with('success', 'Permisos asignados correctamente');
    }
}
