<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Rol;
use App\Models\Objeto;
use App\Http\Controllers\Controller;

class GestionController extends Controller
{
    public function storeRol(Request $request)
    {
        $request->validate([
            'Rol' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:255',
        ]);
        Rol::create([
            'Rol' => $request->Rol,
            'Descripcion' => $request->Descripcion,
        ]);
        return back()->with('success', 'Rol creado correctamente');
    }

    public function storeObjeto(Request $request)
    {
        $request->validate([
            'Objeto' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:255',
            'Tipo_Objeto' => 'nullable|string|max:60',
        ]);
        Objeto::create([
            'Objeto' => $request->Objeto,
            'Descripcion' => $request->Descripcion,
            'Tipo_Objeto' => $request->Tipo_Objeto,
        ]);
        return back()->with('success', 'Objeto creado correctamente');
    }
}
