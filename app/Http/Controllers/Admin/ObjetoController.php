<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Objeto;

class ObjetoController extends Controller
{
    public function index()
    {
        $objetos = Objeto::all();
        return view('admin.objetos', compact('objetos'));
    }

    public function store(Request $request)
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'Objeto' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:255',
            'Tipo_Objeto' => 'nullable|string|max:60',
        ]);
        $objeto = Objeto::findOrFail($id);
        $objeto->update([
            'Objeto' => $request->Objeto,
            'Descripcion' => $request->Descripcion,
            'Tipo_Objeto' => $request->Tipo_Objeto,
        ]);
        return back()->with('success', 'Objeto actualizado correctamente');
    }

    public function destroy($id)
    {
        $objeto = Objeto::findOrFail($id);
        $objeto->delete();
        return back()->with('success', 'Objeto eliminado correctamente');
    }
}
