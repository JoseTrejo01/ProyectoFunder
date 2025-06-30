<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use App\Models\Objeto;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Notifications\CredencialesUsuarioNuevo;



//CONTROLADOR PARA QUE EL ADMIN CREE UN NUEVO USUARIO, ACTULICE O ELIMINE UN USUARIO
class UsuarioController extends Controller
{
    public function index()
    {
        // Obtener el objeto correspondiente a la vista de usuarios
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
            'Usuario' => ['required', 'string', 'max:40', 'unique:tbl_ms_usuario,Usuario'],
            'Nombre_Usuario' => ['required', 'string', 'max:40'],
            'Correo_Electronico' => ['required', 'string', 'email', 'max:60', 'unique:tbl_ms_usuario,Correo_Electronico'],
            'Id_Rol' => ['required', 'integer', 'exists:tbl_ms_rol,Id_Rol'],
            'Estado_Usuario' => ['required', 'string'],
        ], [
            'Usuario.required' => 'El campo usuario es obligatorio',
            'Usuario.max' => 'El usuario no puede tener más de 40 caracteres.',
            'Usuario.unique' => 'El usuario ya está registrado.',
            'Nombre_Usuario.required' => 'El campo nombre de usuario es obligatorio',
            'Nombre_Usuario.max' => 'El nombre de usuario no puede tener más de 40 caracteres.',
            'Correo_Electronico.required' => 'El campo correo electrónico es obligatorio',
            'Correo_Electronico.max' => 'El correo electrónico no puede tener más de 60 caracteres.',
            'Correo_Electronico.unique' => 'Este correo ya está registrado.',
            'Correo_Electronico.email' => 'Debe ingresar un correo electrónico válido con @.',
        ]);

        $fechaCreacion = now();
        $diasVigencia = (int) \DB::table('tbl_parametros')
            ->where('Nombre_Parametro', 'ADMIN_DIAS_VIGENCIA')
            ->value('Valor');
        $fechaVencimiento = $fechaCreacion->copy()->addDays($diasVigencia);

        // Generar contraseña aleatoria segura
        $password = bin2hex(random_bytes(4)); // 8 caracteres hexadecimales

        $nuevoUsuario = User::create([
            'Usuario' => strtoupper($request->Usuario),
            'Nombre_Usuario' => strtoupper($request->Nombre_Usuario),
            'Correo_Electronico' => $request->Correo_Electronico,
            'Id_Rol' => $request->Id_Rol,
            'Primer_Ingreso' => 1,
            'Contraseña' => Hash::make($password),
            'Estado_Usuario' => 'NUEVO',
            'Fecha_Creacion' => $fechaCreacion,
            'Fecha_Vencimiento' => $fechaVencimiento,
        ]);

        // Enviar notificación con credenciales
        $nuevoUsuario->notify(new CredencialesUsuarioNuevo($nuevoUsuario, $password));

        // Registrar en bitácora la creación de un nuevo usuario
        $objeto = Objeto::where('Objeto', 'Usuarios')->first();
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Nuevo',
                ' Creó un nuevo usuario: ' . $nuevoUsuario->Usuario
            );
        }

        return back()->with('success', 'Usuario creado correctamente. Se enviaron las credenciales al correo.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            //'Usuario' => 'required|string|max:60|unique:tbl_ms_usuario,Usuario,' . $id . ',Id_Usuario',
            'Nombre_Usuario' => 'required|string|max:100',
            'Correo_Electronico' => 'required|email|max:60|unique:tbl_ms_usuario,Correo_Electronico,' . $id . ',Id_Usuario',
            'Id_Rol' => 'required|integer|exists:tbl_ms_rol,Id_Rol',
            'Estado_Usuario' => 'required|string',
        ]);

        $usuario = User::findOrFail($id);
        $diasVigencia = (int) \DB::table('tbl_parametros')
            ->where('Nombre_Parametro', 'ADMIN_DIAS_VIGENCIA')
            ->value('Valor');
        $fechaVencimiento = now()->copy()->addDays($diasVigencia);
        $updateData = [
            'Usuario' => $request->Usuario,
            'Nombre_Usuario' => $request->Nombre_Usuario,
            'Correo_Electronico' => $request->Correo_Electronico,
            'Id_Rol' => $request->Id_Rol,
            'Estado_Usuario' => $request->Estado_Usuario,
            'Fecha_Vencimiento' => $fechaVencimiento,
        ];
        $usuario->update($updateData);

        // Registrar en bitácora la actualización de usuario
        $objeto = Objeto::where('Objeto', 'Usuarios')->first();
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Update',
                'El usuario actualizó al usuario: ' . $usuario->Usuario
            );
        }

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->update(['Estado_Usuario' => 'INACTIVO']);

        // Registrar en bitácora la eliminación (inactivación) de usuario
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
