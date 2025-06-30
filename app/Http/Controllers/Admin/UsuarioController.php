<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use App\Models\Objeto;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class UsuarioController extends Controller
{
    public function index()
    {
        $objeto = Objeto::where('Objeto', 'Usuarios')->first();
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Ingreso',
                'El usuario ingresó a la gestión de usuarios'
            );
        }

        $usuarios = User::with('rol')->orderBy('Id_Usuario', 'desc')->get();
        $roles = Rol::all();
        return view('admin.usuarios', compact('usuarios', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Usuario' => 'required|string|max:30|regex:/^[A-Z0-9]+$/|unique:tbl_ms_usuario,Usuario',
            'Nombre_Usuario' => 'required|string|max:100|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/',
            'Correo_Electronico' => 'required|email|max:60|unique:tbl_ms_usuario,Correo_Electronico',
            'Id_Rol' => 'required|integer|exists:tbl_ms_rol,Id_Rol',
            'Contraseña' => 'required|string|min:8',
        ], [
            'Usuario.regex' => 'El usuario solo debe contener letras mayúsculas y números.',
            'Nombre_Usuario.regex' => 'El nombre solo debe contener letras mayúsculas y espacios.',
            'Correo_Electronico.email' => 'Debe ingresar un correo electrónico válido con @.',
            'Correo_Electronico.unique' => 'Este correo ya está registrado.',
            'Usuario.unique' => 'Este nombre de usuario ya existe.',
            'Usuario.max' => 'El usuario no puede tener más de 30 caracteres.',
        ]);

        $fechaCreacion = now();
        $diasVigencia = (int) \DB::table('tbl_parametros')
            ->where('Nombre_Parametro', 'ADMIN_DIAS_VIGENCIA')
            ->value('Valor');
        $fechaVencimiento = $fechaCreacion->copy()->addDays($diasVigencia);

        $nuevoUsuario = User::create([
            'Usuario' => strtoupper($request->Usuario),
            'Nombre_Usuario' => strtoupper($request->Nombre_Usuario),
            'Correo_Electronico' => $request->Correo_Electronico,
            'Id_Rol' => $request->Id_Rol,
            'Primer_Ingreso' => 1,
            'Contraseña' => Hash::make($request->Contraseña),
            'Estado_Usuario' => 'NUEVO',
            'Fecha_Creacion' => $fechaCreacion,
            'Fecha_Vencimiento' => $fechaVencimiento,
        ]);

        $objeto = Objeto::where('Objeto', 'Usuarios')->first();
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Nuevo',
                'Creó un nuevo usuario: ' . $nuevoUsuario->Usuario
            );
        }

        return back()->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Usuario' => 'required|string|max:30|regex:/^[A-Z0-9]+$/|unique:tbl_ms_usuario,Usuario,' . $id . ',Id_Usuario',
            'Nombre_Usuario' => 'required|string|max:100|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/',
            'Correo_Electronico' => 'required|email|max:60|unique:tbl_ms_usuario,Correo_Electronico,' . $id . ',Id_Usuario',
            'Id_Rol' => 'required|integer|exists:tbl_ms_rol,Id_Rol',
            'Estado_Usuario' => 'required|string',
        ], [
            'Usuario.regex' => 'El usuario solo debe contener letras mayúsculas y números.',
            'Nombre_Usuario.regex' => 'El nombre solo debe contener letras mayúsculas y espacios.',
            'Correo_Electronico.email' => 'Debe ingresar un correo electrónico válido con @.',
            'Correo_Electronico.unique' => 'Este correo ya está registrado.',
            'Usuario.unique' => 'Este nombre de usuario ya existe.',
        ]);

        $usuario = User::findOrFail($id);

        $updateData = [
            'Usuario' => strtoupper($request->Usuario),
            'Nombre_Usuario' => strtoupper($request->Nombre_Usuario),
            'Correo_Electronico' => $request->Correo_Electronico,
            'Id_Rol' => $request->Id_Rol,
            'Estado_Usuario' => $request->Estado_Usuario,
        ];

        if ($request->Estado_Usuario === 'ACTIVO') {
            $updateData['Primer_Ingreso'] = 0;
        }

        $usuario->update($updateData);

        $objeto = Objeto::where('Objeto', 'Usuarios')->first();
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Update',
                'Actualizó al usuario: ' . $usuario->Usuario
            );
        }

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->update(['Estado_Usuario' => 'INACTIVO']);

        $objeto = Objeto::where('Objeto', 'Usuarios')->first();
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Delete',
                'Inactivó al usuario: ' . $usuario->Usuario
            );
        }

        return back()->with('success', 'Usuario eliminado correctamente.');
    }
}
