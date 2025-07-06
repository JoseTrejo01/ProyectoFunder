<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rol;

class RolController extends Controller
{
    public function index()
    {
        $roles = Rol::all();
        return view('admin.roles', compact('roles'));
    }

    public function store(Request $request)
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'Rol' => 'required|string|max:100',
            'Descripcion' => 'nullable|string|max:255',
        ]);
        $rol = Rol::findOrFail($id);
        $rol->update([
            'Rol' => $request->Rol,
            'Descripcion' => $request->Descripcion,
        ]);
        return back()->with('success', 'Rol actualizado correctamente');
    }

    public function destroy($id)
    {
        $rol = Rol::findOrFail($id);
        $rol->delete();
        return back()->with('success', 'Rol eliminado correctamente');
    }
}
