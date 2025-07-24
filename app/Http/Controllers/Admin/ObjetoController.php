<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Objeto;
use Illuminate\Support\Facades\Auth;
<<<<<<< HEAD

=======
>>>>>>> ed6dbce2cb744c0750bb03a701d7e8d1c813c357

class ObjetoController extends Controller
{
  public function index()
{
    // Verificar permiso de consulta
    if (!auth()->user()->tienePermiso('Objetos', 'Consultar')) {
        return view('errors.403', ['mensaje' => 'No tiene permiso para consultar objetos']);
    }
    $objetos = Objeto::where('Estado', 'ACTIVO')->get();
    // Registrar en bitácora el ingreso a la gestión de objetos
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
    // Verificar permiso de inserción
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
    // Registrar en bitácora la creación de un nuevo objeto
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
    // Verificar permiso de actualización
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
    // Registrar en bitácora la actualización de objeto
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
    // Verificar permiso de eliminación
    if (!auth()->user()->tienePermiso('Objetos', 'Eliminacion')) {
        return view('errors.403', ['mensaje' => 'No tiene permiso para eliminar objetos']);
    }
    $objetoEdit = Objeto::findOrFail($id);
    $objetoEdit->Estado = 'INACTIVO';
    $objetoEdit->save();
    // Registrar en bitácora la inactivación de objeto
    $objeto = Objeto::where('Objeto', 'Objetos')->first();
    if ($objeto && Auth::check()) {
        EVENT_BITACORA(
            Auth::user()->Id_Usuario,
            $objeto->Id_Objeto,
            'Delete',
            'Inactivó el objeto: ' . $objetoEdit->Objeto
        );
    }
    return back()->with('success', 'Objeto inactivado correctamente');
}
}
