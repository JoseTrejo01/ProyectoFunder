<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
            'Usuario' => ['required', 'string'],
            'Contraseña' => ['required', 'string', 'regex:/^\S*$/u']
        ], [
            'Usuario.required' => 'El campo usuario es obligatorio',
            'Contraseña.required' => 'El campo contraseña es obligatorio',
            'Contraseña.regex' => 'La contraseña no puede contener espacios'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 2. Convertir usuario a mayúsculas
        $usuario = strtoupper($request->Usuario);

        // 3. Buscar usuario
        $user = User::where('Usuario', $usuario)->first();

        if (!$user) {
            return back()->withErrors([
                'Usuario' => 'Usuario/contraseña inválidos'
            ])->withInput();
        }

        // 4. Verificar si es auto-registro (pendiente)
        if ($user->Id_Rol == 3) {
            return back()->withErrors([
                'Usuario' => 'Tu usuario está pendiente de aprobación. Por favor, contacta a la administración.'
            ])->withInput();
        }

        // 5. Verificar estado activo
        if (strtoupper(trim($user->Estado_Usuario)) !== 'ACTIVO') {
            return back()->withErrors([
                'Usuario' => 'El usuario no está activo'
            ])->withInput();
        }

        // 6. Verificar contraseña
        if (!Hash::check($request->Contraseña, $user->Contraseña)) {
            return back()->withErrors([
                'Usuario' => 'Usuario/contraseña inválidos'
            ])->withInput();
        }

        // 7. Verificar si el correo fue confirmado
        if (is_null($user->email_verified_at)) {
            return back()->withErrors([
                'Usuario' => 'Debes verificar tu correo electrónico antes de iniciar sesión.'
            ])->withInput();
        }

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
