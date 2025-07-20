<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Rol;
use Illuminate\Support\Facades\Auth;


class RolController extends Controller
{
   public function index()
{
    // Verificar permiso de consulta
    if (!auth()->user()->tienePermiso('Roles', 'Consultar')) {
        return view('errors.403', ['mensaje' => 'No tiene permiso para consultar roles']);
    }
    $roles = Rol::all();
    // Registrar en bitácora el ingreso a la gestión de roles
    $objeto = \App\Models\Objeto::where('Objeto', 'Roles')->first();
    if ($objeto && \Auth::check()) {
        EVENT_BITACORA(
            \Auth::user()->Id_Usuario,
            $objeto->Id_Objeto,
            'Ingreso',
            'El usuario ingresó a la gestión de roles'
        );
    }
    return view('admin.roles', compact('roles'));
}

public function store(Request $request)
{
    // Verificar permiso de inserción
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
    // Registrar en bitácora la creación de un nuevo rol
    $objeto = \App\Models\Objeto::where('Objeto', 'Roles')->first();
    if ($objeto && \Auth::check()) {
        EVENT_BITACORA(
            \Auth::user()->Id_Usuario,
            $objeto->Id_Objeto,
            'Nuevo',
            'Creó un nuevo rol: ' . $nuevoRol->Rol
        );
    }
    return back()->with('success', 'Rol creado correctamente');
}

public function update(Request $request, $id)
{
    // Verificar permiso de actualización
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
    // Registrar en bitácora la actualización de rol
    $objeto = \App\Models\Objeto::where('Objeto', 'Roles')->first();
    if ($objeto && \Auth::check()) {
        EVENT_BITACORA(
            \Auth::user()->Id_Usuario,
            $objeto->Id_Objeto,
            'Update',
            'Actualizó el rol: ' . $rol->Rol
        );
    }
    return back()->with('success', 'Rol actualizado correctamente');
}

public function destroy($id)
{
    // Verificar permiso de eliminación
    if (!auth()->user()->tienePermiso('Roles', 'Eliminacion')) {
        return view('errors.403', ['mensaje' => 'No tiene permiso para eliminar roles']);
    }
    $rol = Rol::findOrFail($id);
    // Quitar el rol a los usuarios relacionados (poner en null)
    \App\Models\User::where('Id_Rol', $rol->Id_Rol)->update(['Id_Rol' => null]);
    $rol->delete();
    // Registrar en bitácora la eliminación de rol
    $objeto = \App\Models\Objeto::where('Objeto', 'Roles')->first();
    if ($objeto && \Auth::check()) {
        EVENT_BITACORA(
            \Auth::user()->Id_Usuario,
            $objeto->Id_Objeto,
            'Delete',
            'Eliminó el rol: ' . $rol->Rol
        );
    }
    return back()->with('success', 'Rol eliminado correctamente');
}

}