<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Objeto;
use Illuminate\Support\Facades\Auth;

class ObjetoController extends Controller
{
    public function index()
    {
        if (!auth()->user()->tienePermiso('Objetos', 'Consultar')) {
            return view('errors.403', ['mensaje' => 'No tiene permiso para consultar objetos']);
        }

        // Filtrar solo objetos activos
        $objetos = Objeto::where('Estado', 'ACTIVO')->get();

        $objeto = Objeto::where('Objeto', 'Objetos')->first();
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Ingreso',
                'El usuario ingresó a la gestión de objetos'
            );
        }

        return view('admin.objetos', compact('objetos'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->tienePermiso('Objetos', 'Insercion')) {
            return view('errors.403', ['mensaje' => 'No tiene permiso para crear objetos']);
        }

        $request->validate([
            'Objeto' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:255',
            'Tipo_Objeto' => 'nullable|string|max:60',
        ]);

        $nuevoObjeto = Objeto::create([
            'Objeto' => $request->Objeto,
            'Descripcion' => $request->Descripcion,
            'Tipo_Objeto' => $request->Tipo_Objeto,
        ]);

        $objeto = Objeto::where('Objeto', 'Objetos')->first();
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Nuevo',
                'Creó un nuevo objeto: ' . $nuevoObjeto->Objeto
            );
        }

        return back()->with('success', 'Objeto creado correctamente');
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->tienePermiso('Objetos', 'Actualizacion')) {
            return view('errors.403', ['mensaje' => 'No tiene permiso para actualizar objetos']);
        }

        $request->validate([
            'Objeto' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:255',
            'Tipo_Objeto' => 'nullable|string|max:60',
        ]);

        $objetoEdit = Objeto::findOrFail($id);
        $objetoEdit->update([
            'Objeto' => $request->Objeto,
            'Descripcion' => $request->Descripcion,
            'Tipo_Objeto' => $request->Tipo_Objeto,
        ]);

        $objeto = Objeto::where('Objeto', 'Objetos')->first();
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Update',
                'Actualizó el objeto: ' . $objetoEdit->Objeto
            );
        }

        return back()->with('success', 'Objeto actualizado correctamente');
    }

    public function destroy($id)
    {
        if (!auth()->user()->tienePermiso('Objetos', 'Eliminacion')) {
            return view('errors.403', ['mensaje' => 'No tiene permiso para eliminar objetos']);
        }

        $objetoEdit = Objeto::findOrFail($id);

        // Verificar si hay roles con permisos asignados a este objeto
        $rolesConPermisos = \App\Models\RolesObjeto::where('Id_Objeto', $objetoEdit->Id_Objeto)->count();
        
        if ($rolesConPermisos > 0) {
            return back()->with('error', 'No se puede desactivar el objeto porque tiene permisos asignados a roles. Primero elimine los permisos.');
        }

        // Desactivar el objeto en lugar de eliminarlo
        $objetoEdit->Estado = 'INACTIVO';
        $objetoEdit->save();

        $objeto = Objeto::where('Objeto', 'Objetos')->first();
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Delete',
                'Desactivó el objeto: ' . $objetoEdit->Objeto
            );
        }

        return back()->with('success', 'Objeto desactivado correctamente');
    }
}
