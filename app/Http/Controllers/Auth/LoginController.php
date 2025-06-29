<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Parametro;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('Auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validación de campos requeridos
        $validator = Validator::make($request->all(), [
            'Usuario' => ['required', 'string','max:30'],
            'Contraseña' => ['required', 'string', 'size:8', 'regex:/^\S*$/u']
        ], [
            'Usuario.required' => 'El campo usuario es obligatorio',
            'Usuario.max' => 'El usuario no puede tener más de 30 caracteres.',
            'Contraseña.required' => 'El campo contraseña es obligatorio',
            'Contraseña.size' => 'La contraseña debe tener exactamente 8 caracteres.',
            'Contraseña.regex' => 'La contraseña no puede contener espacios'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // 2. Convertir usuario a mayúsculas
        $usuario = strtoupper($request->Usuario);

       $user = User::where('Usuario', $usuario)->first();

        if (!$user) {
            return back()->withErrors([
                'Usuario' => 'Usuario/contraseña inválidos'
            ])->withInput();
        }

        // 4. Obtener límite de intentos desde la tabla de parámetros
        $parametro = Parametro::where('Nombre_Parametro', 'ADMIN_INTENTOS_INVALIDOS')->first();
        $limiteIntentos = $parametro ? intval($parametro->Valor) : 3; // fallback a 3 si no existe

        // 5. Verificar si ya está bloqueado
        if (strtoupper(trim($user->Estado_Usuario)) === 'BLOQUEADO') {
            return back()->withErrors([
                'Usuario' => 'Tu cuenta ha sido bloqueada por múltiples intentos fallidos.'
            ])->withInput();
        }

        // 6. Verificar contraseña
        if (!Hash::check($request->Contraseña, $user->Contraseña)) {
            // Incrementar intentos fallidos
            $user->Intentos_Fallidos = ($user->Intentos_Fallidos ?? 0) + 1;

            // Verificar si alcanzó el límite
            if ($user->Intentos_Fallidos >= $limiteIntentos) {
                $user->Estado_Usuario = 'BLOQUEADO';
            }

            $user->save();

            return back()->withErrors([
                'Usuario' => $user->Estado_Usuario === 'BLOQUEADO'
                    ? 'Tu cuenta ha sido bloqueada por múltiples intentos fallidos.'
                    : 'Usuario/contraseña inválidos'
            ])->withInput();
        }

        // 7. Verificar si es auto-registro (pendiente)
        if ($user->Id_Rol == 3) {
            return back()->withErrors([
                'Usuario' => 'Tu usuario está pendiente de aprobación. Por favor, contacta a la administración.'
            ])->withInput();
        }

        // 8. Verificar estado activo
        if (strtoupper(trim($user->Estado_Usuario)) !== 'ACTIVO') {
            return back()->withErrors([
                'Usuario' => 'El usuario no está activo'
            ])->withInput();
        }

        // 9. Reiniciar intentos fallidos
        $user->Intentos_Fallidos = 0;
        $user->save();
        
        // 8. Autenticar y redirigir
        Auth::login($user);
        $request->session()->regenerate();

        EVENT_BITACORA($user->Id_Usuario, 1, 'Ingreso', 'El usuario ha iniciado sesión.');

        return redirect()->intended('/dashboard');

    }

    public function logout(Request $request)
    {
        EVENT_BITACORA(Auth::user()->Id_Usuario, 2, 'Salida', 'El usuario ha cerrado sesión.');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Sesión cerrada correctamente');
    }
}
