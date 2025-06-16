<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with('rol')->get();
        $roles = Rol::all();
        return view('admin.usuarios', compact('usuarios', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Usuario' => 'required|string|max:60|unique:tbl_ms_usuario,Usuario',
            'Nombre_Usuario' => 'required|string|max:100',
            'Correo_Electronico' => 'required|email|max:60|unique:tbl_ms_usuario,Correo_Electronico',
            'Id_Rol' => 'required|integer|exists:tbl_ms_rol,Id_Rol',
            'Contraseña' => 'required|string|min:8',
        ]);

        User::create([
            'Usuario' => $request->Usuario,
            'Nombre_Usuario' => $request->Nombre_Usuario,
            'Correo_Electronico' => $request->Correo_Electronico,
            'Id_Rol' => $request->Id_Rol,
            'Contraseña' => Hash::make($request->Contraseña),
            'Estado_Usuario' => 'ACTIVO',
            'Fecha_Creacion' => now(),
        ]);

        return back()->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Usuario' => 'required|string|max:60|unique:tbl_ms_usuario,Usuario,' . $id . ',Id_Usuario',
            'Nombre_Usuario' => 'required|string|max:100',
            'Correo_Electronico' => 'required|email|max:60|unique:tbl_ms_usuario,Correo_Electronico,' . $id . ',Id_Usuario',
            'Id_Rol' => 'required|integer|exists:tbl_ms_rol,Id_Rol',
            'Estado_Usuario' => 'required|string',
        ]);

        $usuario = User::findOrFail($id);
        $usuario->update([
            'Usuario' => $request->Usuario,
            'Nombre_Usuario' => $request->Nombre_Usuario,
            'Correo_Electronico' => $request->Correo_Electronico,
            'Id_Rol' => $request->Id_Rol,
            'Estado_Usuario' => $request->Estado_Usuario,
        ]);

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();
        return back()->with('success', 'Usuario eliminado correctamente.');
    }
}
