<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rol;
use App\Models\User;
use App\Models\Objeto;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class RolController extends Controller
{
    public function index()
    {
        if (!auth()->user()->tienePermiso('Roles', 'Consultar')) {
            return view('errors.403', ['mensaje' => 'No tiene permiso para consultar roles']);
        }

        $roles = Rol::where('Estado', 'ACTIVO')->get();

        $objeto = Objeto::where('Objeto', 'Roles')->first();
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Ingreso',
                'El usuario ingresó a la gestión de roles'
            );
        }

        return view('admin.roles', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->tienePermiso('Roles', 'Insercion')) {
            return view('errors.403', ['mensaje' => 'No tiene permiso para crear roles']);
        }

        $request->validate([
            'Rol' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:255',
        ]);

        $nuevoRol = Rol::create([
            'Rol' => $request->Rol,
            'Descripcion' => $request->Descripcion,
        ]);

        $objeto = Objeto::where('Objeto', 'Roles')->first();
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Nuevo',
                'Creó un nuevo rol: ' . $nuevoRol->Rol
            );
        }

        return back()->with('success', 'Rol creado correctamente');
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->tienePermiso('Roles', 'Actualizacion')) {
            return view('errors.403', ['mensaje' => 'No tiene permiso para actualizar roles']);
        }

        $request->validate([
            'Rol' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:255',
        ]);

        $rol = Rol::findOrFail($id);
        $rol->update([
            'Rol' => $request->Rol,
            'Descripcion' => $request->Descripcion,
        ]);

        $objeto = Objeto::where('Objeto', 'Roles')->first();
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Update',
                'Actualizó el rol: ' . $rol->Rol
            );
        }

        return back()->with('success', 'Rol actualizado correctamente');
    }

    public function destroy($id)
    {
        if (!auth()->user()->tienePermiso('Roles', 'Eliminacion')) {
            return view('errors.403', ['mensaje' => 'No tiene permiso para eliminar roles']);
        }

        $rol = Rol::findOrFail($id);

        // Verificar si hay usuarios asignados a este rol
        $usuariosConEsteRol = User::where('Id_Rol', $rol->Id_Rol)->count();
        
        if ($usuariosConEsteRol > 0) {
            return back()->with('error', 'No se puede desactivar el rol porque tiene usuarios asignados. Primero cambie el rol de los usuarios.');
        }

        // Desactivar el rol en lugar de eliminarlo
        $rol->Estado = 'INACTIVO';
        $rol->save();

        $objeto = Objeto::where('Objeto', 'Roles')->first();
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Delete',
                'Desactivó el rol: ' . $rol->Rol
            );
        }

        return back()->with('success', 'Rol desactivado correctamente');
    }

 
public function exportarPDF()
{
    if (!auth()->user()->tienePermiso('Roles', 'Consultar')) {
        return view('errors.403', ['mensaje' => 'No tiene permiso para exportar roles']);
    }

    $roles = Rol::where('Estado', 'ACTIVO')->get();

    $pdf = Pdf::loadView('admin.reportes.roles_pdf', [
    'roles' => $roles,
    'pdf' => true, 
])
              ->setPaper('a4', 'portrait');
      $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
   $pdf->getDomPDF()->set_option('isPhpEnabled', true);

    return $pdf->download('reporte_roles.pdf');
}

}

