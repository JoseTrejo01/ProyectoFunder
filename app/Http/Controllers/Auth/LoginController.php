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
        // 1. Validación de campos
        $validator = Validator::make($request->all(), [
            'Usuario' => ['required', 'string', 'max:30'],
            'Contraseña' => ['required', 'string', 'size:8', 'regex:/^\S*$/u']
        ], [
            'Usuario.required' => 'El campo usuario es obligatorio.',
            'Usuario.max' => 'El usuario no puede tener más de 30 caracteres.',
            'Contraseña.required' => 'El campo contraseña es obligatorio.',
            'Contraseña.size' => 'La contraseña debe tener exactamente 8 caracteres.',
            'Contraseña.regex' => 'La contraseña no puede contener espacios.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 2. Convertir usuario a mayúsculas
        $usuario = strtoupper($request->Usuario);

        $user = User::where('Usuario', $usuario)->first();

        if (!$user) {
            return back()->withErrors([
                'Usuario' => 'Usuario/contraseña inválidos.'
            ])->withInput();
        }

        // 3. Intentos fallidos y bloqueo
        $parametro = Parametro::where('Nombre_Parametro', 'ADMIN_INTENTOS_INVALIDOS')->first();
        $limiteIntentos = $parametro ? intval($parametro->Valor) : 3;

        if (strtoupper(trim($user->Estado_Usuario)) === 'BLOQUEADO') {
            return back()->withErrors([
                'Usuario' => 'Tu cuenta está bloqueada por múltiples intentos fallidos.'
            ])->withInput();
        }

        // 4. Verificar contraseña
        if (!Hash::check($request->Contraseña, $user->Contraseña)) {
            $user->Intentos_Fallidos = ($user->Intentos_Fallidos ?? 0) + 1;
            if ($user->Intentos_Fallidos >= $limiteIntentos) {
                $user->Estado_Usuario = 'BLOQUEADO';
            }
            $user->save();

            return back()->withErrors([
                'Usuario' => $user->Estado_Usuario === 'BLOQUEADO'
                    ? 'Tu cuenta ha sido bloqueada por múltiples intentos fallidos.'
                    : 'Usuario/contraseña inválidos.'
            ])->withInput();
        }

        // 5. Verificar auto-registro (rol 3)
        if ($user->Id_Rol == 3) {
            return back()->withErrors([
                'Usuario' => 'Tu usuario está pendiente de aprobación. Por favor, contacta a la administración.'
            ])->withInput();
        }

        // 6. Verificar estado activo
        if (strtoupper(trim($user->Estado_Usuario)) !== 'ACTIVO') {
            return back()->withErrors([
                'Usuario' => 'El usuario no está activo.'
            ])->withInput();
        }

        // 7. Verificar email confirmado
        if (is_null($user->email_verified_at)) {
            return back()->withErrors([
                'Usuario' => 'Debes verificar tu correo electrónico antes de iniciar sesión.'
            ])->withInput();
        }

        // 8. Reiniciar intentos fallidos al ingresar correctamente
        $user->Intentos_Fallidos = 0;
        $user->save();

        // 9. Autenticar y redirigir
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
