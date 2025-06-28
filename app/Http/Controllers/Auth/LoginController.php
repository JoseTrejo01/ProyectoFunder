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

  
    // 6. Verificar contraseña manualmente
    if (Hash::check($request->Contraseña, $user->Contraseña)) {
        // Hacer Login 
        Auth::login($user);
        $request->session()->regenerate();

        // Si el usuario tenía Primer_Ingreso en 1, actualizarlo a 0 tras el primer logeo
        if ($user->Primer_Ingreso == 1) {
            $user->Primer_Ingreso = 0;
            $user->save();
        }

        EVENT_BITACORA($user->Id_Usuario, 1, 'Ingreso', 'El usuario ha iniciado sesión.');

        return redirect()->intended('/dashboard');
    }

      // 5. Verificar si el usuario está activo
    if (strtoupper(trim($user->Estado_Usuario)) !== 'ACTIVO') {
        return back()->withErrors([
            'Usuario' => 'El usuario no está activo'
        ])->withInput();
    }


    return back()->withErrors([
        'Usuario' => 'Usuario/contraseña inválidos'
    ])->withInput(); 
    
}


 
public function logout(Request $request)
{

    // Registrar en la bitácora
   
    EVENT_BITACORA(Auth::user()->Id_Usuario, 2, 'Salida', 'El usuario ha cerrado sesión.');
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Sesión cerrada correctamente');
    }
}
