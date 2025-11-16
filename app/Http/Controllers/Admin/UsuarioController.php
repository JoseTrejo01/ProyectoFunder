<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use App\Models\Objeto;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\CredencialesUsuarioNuevo;
use Barryvdh\DomPDF\Facade\Pdf;

class UsuarioController extends Controller
{
    // Listar usuarios
    public function index()
    {
        if (!auth()->user()->tienePermiso('Usuarios', 'Consultar')) {
            return view('errors.403', ['mensaje' => 'No tiene permiso para consultar usuarios']);
        }

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

    // Exportar usuarios a PDF
    public function exportarPDF()
    {
        if (!auth()->user()->tienePermiso('Usuarios', 'Consultar')) {
            return view('errors.403', ['mensaje' => 'No tiene permiso para exportar usuarios']);
        }

        $usuarios = User::with('rol')->orderBy('Id_Usuario', 'desc')->get();

        $pdf = Pdf::loadView('admin.reportes.usuarios_pdf', [
            'usuarios' => $usuarios,
            'pdf'      => true,
        ])->setPaper('a4', 'landscape');

        $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
        $pdf->getDomPDF()->set_option('isPhpEnabled', true);

        return $pdf->download('reporte_usuarios.pdf');
    }

    // Crear nuevo usuario
    public function store(Request $request)
    {
        if (!auth()->user()->tienePermiso('Usuarios', 'Insercion')) {
            return view('errors.403', ['mensaje' => 'No tiene permiso para crear usuarios']);
        }

        $request->validate([
            'Usuario' => ['required', 'string', 'max:60', 'unique:tbl_ms_usuario,Usuario'],
            'Nombre_Usuario' => ['required', 'string', 'max:100'],
            'Correo_Electronico' => ['required', 'string', 'email', 'max:60', 'unique:tbl_ms_usuario,Correo_Electronico'],
            'Id_Rol' => ['required', 'integer', 'exists:tbl_ms_rol,Id_Rol'],
            'Estado_Usuario' => ['required', 'string'],
        ], [
            'Usuario.required' => 'El campo usuario es obligatorio',
            'Usuario.max' => 'El usuario no puede tener más de 60 caracteres.',
            'Usuario.unique' => 'El usuario ya está registrado.',
            'Nombre_Usuario.required' => 'El campo nombre de usuario es obligatorio',
            'Nombre_Usuario.max' => 'El nombre de usuario no puede tener más de 100 caracteres.',
            'Correo_Electronico.required' => 'El campo correo electrónico es obligatorio',
            'Correo_Electronico.max' => 'El correo electrónico no puede tener más de 60 caracteres.',
            'Correo_Electronico.unique' => 'Este correo ya está registrado.',
            'Correo_Electronico.email' => 'Debe ingresar un correo electrónico válido con @.',
        ]);

        $fechaCreacion = now();
        $diasVigencia = (int) DB::table('tbl_parametros')
            ->where('Nombre_Parametro', 'ADMIN_DIAS_VIGENCIA')
            ->value('Valor');
        $fechaVencimiento = $fechaCreacion->copy()->addDays($diasVigencia);

        $password = bin2hex(random_bytes(4)); // 8 caracteres

        $nuevoUsuario = User::create([
            'Usuario'            => strtoupper($request->Usuario),
            'Nombre_Usuario'     => strtoupper($request->Nombre_Usuario),
            'Correo_Electronico' => $request->Correo_Electronico,
            'Id_Rol'             => $request->Id_Rol,
            'Primer_Ingreso'     => 1,
            'Contraseña'         => Hash::make($password),
            'Estado_Usuario'     => 'NUEVO',
            'Fecha_Creacion'     => $fechaCreacion,
            'Fecha_Vencimiento'  => $fechaVencimiento,
        ]);

        $nuevoUsuario->notify(new CredencialesUsuarioNuevo($nuevoUsuario, $password));

        $objeto = Objeto::where('Objeto', 'Usuarios')->first();
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Nuevo',
                'Creó un nuevo usuario: ' . $nuevoUsuario->Usuario
            );
        }

        return back()->with('success', 'Usuario creado correctamente. Se enviaron las credenciales al correo.');
    }

    // Actualizar usuario
    public function update(Request $request, $id)
    {
        if (!auth()->user()->tienePermiso('Usuarios', 'Actualizacion')) {
            return view('errors.403', ['mensaje' => 'No tiene permiso para actualizar usuarios']);
        }

        $request->validate([
            'Usuario' => 'required|string|max:60|unique:tbl_ms_usuario,Usuario,' . $id . ',Id_Usuario',
            'Nombre_Usuario' => 'required|string|max:100',
            'Correo_Electronico' => 'required|email|max:60|unique:tbl_ms_usuario,Correo_Electronico,' . $id . ',Id_Usuario',
            'Id_Rol'             => 'required|integer|exists:tbl_ms_rol,Id_Rol',
            'Estado_Usuario'     => 'required|string',
        ]);

        $usuario = User::findOrFail($id);

        // 🔐 PROTEGER SUPER ADMIN
        if ($usuario->Es_Super_Admin == 1) {
            if ($request->Id_Rol != $usuario->Id_Rol || $request->Estado_Usuario != $usuario->Estado_Usuario) {
                return back()->with('error', 'No puedes modificar el rol o estado del Super Admin.');
            }
        }

        $diasVigencia = (int) DB::table('tbl_parametros')
            ->where('Nombre_Parametro', 'ADMIN_DIAS_VIGENCIA')
            ->value('Valor');
        $fechaVencimiento = now()->copy()->addDays($diasVigencia);

        $usuario->update([
            'Usuario'            => strtoupper($request->Usuario),
            'Nombre_Usuario'     => strtoupper($request->Nombre_Usuario),
            'Correo_Electronico' => $request->Correo_Electronico,
            'Id_Rol'             => $request->Id_Rol,
            'Estado_Usuario'     => $request->Estado_Usuario,
            'Fecha_Vencimiento'  => $fechaVencimiento,
        ]);

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

    // Eliminar (inactivar) usuario
    public function destroy($id)
    {
        if (!auth()->user()->tienePermiso('Usuarios', 'Eliminacion')) {
            return view('errors.403', ['mensaje' => 'No tiene permiso para eliminar usuarios']);
        }

        $usuario = User::findOrFail($id);

        // 🔐 PROTEGER SUPER ADMIN
        if ($usuario->Es_Super_Admin == 1) {
            return back()->with('error', 'No puedes eliminar al Super Admin.');
        }

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

    // =========================
    // NUEVOS MÉTODOS AGREGADOS
    // =========================

    public function bloquear($id)
    {
        // Usa 'Actualizacion' o crea una acción específica 'Bloqueo' en tu matriz de permisos
        if (!auth()->user()->tienePermiso('Usuarios', 'Actualizacion')) {
            return view('errors.403', ['mensaje' => 'No tiene permiso para bloquear usuarios']);
        }

        $usuario = User::findOrFail($id);

        // Evitar auto-bloqueo
        if ((int) $usuario->Id_Usuario === (int) Auth::user()->Id_Usuario) {
            return back()->with('error', 'No puedes bloquear tu propio usuario.');
        }

        // Evitar bloquear super admin (ajusta según tu lógica: Id_Rol, nombre de rol, etc.)
        if ((int) $usuario->Id_Rol === 1) {
            return back()->with('error', 'No puedes bloquear al superadministrador.');
        }

        // Si ya está bloqueado, no hacer nada
        if ($usuario->Estado_Usuario === 'BLOQUEADO') {
            return back()->with('info', 'El usuario ya está bloqueado.');
        }

        $usuario->Estado_Usuario = 'BLOQUEADO';
        $usuario->save();

        $objeto = Objeto::where('Objeto', 'Usuarios')->first();
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Bloqueo',
                "Se bloqueó al usuario {$usuario->Usuario}"
            );
        }

        return back()->with('success', 'Usuario bloqueado.');
    }

    public function desbloquear($id)
    {
        if (!auth()->user()->tienePermiso('Usuarios', 'Actualizacion')) {
            return view('errors.403', ['mensaje' => 'No tiene permiso para desbloquear usuarios']);
        }

        $usuario = User::findOrFail($id);

        // Si ya está activo, no hacer nada
        if ($usuario->Estado_Usuario === 'ACTIVO') {
            return back()->with('info', 'El usuario ya está activo.');
        }

        $usuario->Estado_Usuario = 'ACTIVO';
        $usuario->Intentos_Fallidos = 0; // opcional: limpia intentos si usas throttle propio
        $usuario->save();

        $objeto = Objeto::where('Objeto', 'Usuarios')->first();
        if ($objeto && Auth::check()) {
            EVENT_BITACORA(
                Auth::user()->Id_Usuario,
                $objeto->Id_Objeto,
                'Desbloqueo',
                "Se desbloqueó al usuario {$usuario->Usuario}"
            );
        }

        return back()->with('success', 'Usuario desbloqueado.');
    }
}
