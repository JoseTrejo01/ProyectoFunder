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
    // 1. Validación de campos requeridos y formato
    $validator = Validator::make($request->all(), [
        'Usuario' => [
            'required',
            'string',
        ],
        'Contraseña' => [
            'required', 
            'string',
            'regex:/^\S*$/u' // No permite espacios en blanco
        ]
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

    // 3. Verificar si el usuario existe en la base de datos
    $user = User::where('Usuario', $usuario)->first();

    if (!$user) {
        return back()->withErrors([
            'Usuario' => 'Usuario/contraseña inválidos'
        ])->withInput();
    }

    // 4. Verificar si el usuario es nuevo de aprobación o es AUTO-REGISTRO
    if ($user->Id_Rol == 3 ) {
        return back()->withErrors([
            'Usuario' => 'Tu usuario está pendiente de aprobación. Por favor, contacta a la administración para ser aceptado.'
        ])->withInput();
    }

        // 5. Verificar estado activo o nuevo
        if (strtoupper(trim($user->Estado_Usuario)) !== 'ACTIVO' && strtoupper(trim($user->Estado_Usuario)) !== 'NUEVO') {
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
        // Si el usuario está en estado NUEVO, forzar cambio de contraseña
        if (strtoupper(trim($user->Estado_Usuario)) === 'NUEVO') {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->route('password.change.form');
        }

        // Si es primer ingreso, forzar doble verificación OTP
        if ($user->Primer_Ingreso == 1) {
            // Generar y guardar OTP
            $otp = rand(100000, 999999);
            $expiration = now()->addMinutes(10);
            $user->otp_code = $otp;
            $user->otp_expires_at = $expiration;
            $user->save();

            \Mail::raw("Tu código de verificación es: $otp", function ($message) use ($user) {
                $message->to($user->Correo_Electronico)
                        ->subject('Código de verificación OTP');
            });

            session(['otp_validated_user' => $user->Usuario]);
            return redirect()->route('otp.form')->with('status', 'Código enviado a tu correo electrónico');
        }

        // Hacer Login solo si ya no es NUEVO ni Primer_Ingreso
        Auth::login($user);
        $request->session()->regenerate();

        // Si el usuario tenía Primer_Ingreso en 1, actualizarlo a 0 tras el primer logeo (solo si no es NUEVO)
        if ($user->Primer_Ingreso == 1 && strtoupper(trim($user->Estado_Usuario)) !== 'NUEVO') {
            $user->Primer_Ingreso = 0;
            $user->save();
        }

        //Registrar en la bitacora
        EVENT_BITACORA($user->Id_Usuario, 1, 'Ingreso', 'El usuario ha iniciado sesión.');

        // Redirigir al dashboard 
        return redirect()->intended('/dashboard');
    }

      // 5. Verificar si el usuario está activo (excepto si es NUEVO)
    if (strtoupper(trim($user->Estado_Usuario)) !== 'ACTIVO' && strtoupper(trim($user->Estado_Usuario)) !== 'NUEVO') {
        return back()->withErrors([
            'Usuario' => 'El usuario no está activo'
        ])->withInput();
    }


    return back()->withErrors([
        'Usuario' => 'Usuario/contraseña inválidos'
    ])->withInput(); 
    
}


    public function showChangePasswordForm()
    {
        return view('auth.passwords.cambiar-contraseña');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->Contraseña = Hash::make($request->password);
        $user->Estado_Usuario = 'ACTIVO';
        $user->save();

        // Cerrar sesión y redirigir al login
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Contraseña cambiada correctamente. Por favor, inicia sesión con tu nueva contraseña.');
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
