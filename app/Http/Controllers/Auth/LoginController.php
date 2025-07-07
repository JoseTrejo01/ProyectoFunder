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

        // 3. Obtener límite de intentos desde la tabla de parámetros
        $parametro = Parametro::where('Nombre_Parametro', 'ADMIN_INTENTOS_INVALIDOS')->first();
        $limiteIntentos = $parametro ? intval($parametro->Valor) : 3; // fallback a 3 si no existe

        // 4. Verificar si el usuario ya está bloqueado
        if (strtoupper(trim($user->Estado_Usuario)) === 'BLOQUEADO') {
            return back()->withErrors([
                'Usuario' => 'Tu cuenta ha sido bloqueada por múltiples intentos fallidos.'
            ])->withInput();
        }

        // 5. Verificar si el usuario es nuevo de aprobación o es AUTO-REGISTRO
        if ($user->Id_Rol == 3 ) {
            return back()->withErrors([
                'Usuario' => 'Tu usuario está pendiente de aprobación. Por favor, contacta a la administración para ser aceptado.'
            ])->withInput();
        }

        // 6. Verificar estado activo o nuevo
        if (strtoupper(trim($user->Estado_Usuario)) !== 'ACTIVO' && strtoupper(trim($user->Estado_Usuario)) !== 'NUEVO') {
            return back()->withErrors([
                'Usuario' => 'El usuario no está activo'
            ])->withInput();
        }

        // 7. Verificar contraseña
        if (!Hash::check($request->Contraseña, $user->Contraseña)) {
            // Incrementar intentos fallidos
            $user->Intentos_Fallidos = ($user->Intentos_Fallidos ?? 0) + 1;
            // Verificar si alcanzó el límite
            if ($user->Intentos_Fallidos >= $limiteIntentos) {
                $user->Estado_Usuario = 'BLOQUEADO';
                // Registrar en bitácora el bloqueo por intentos fallidos
                try {
                    $objeto = \App\Models\Objeto::where('Objeto', 'Usuarios')->first();
                    if ($objeto) {
                        EVENT_BITACORA(
                            $user->Id_Usuario,
                            $objeto->Id_Objeto,
                            'Bloqueo',
                            'El usuario fue bloqueado por intentos fallidos de inicio de sesión.'
                        );
                    }
                } catch (\Throwable $e) {}
            }
            $user->save();
            return back()->withErrors([
                'Usuario' => $user->Estado_Usuario === 'BLOQUEADO'
                    ? 'Tu cuenta ha sido bloqueada'
                    : 'Usuario/contraseña inválidos'
            ])->withInput();
        }

<<<<<<< HEAD
  
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

=======
        // 8. Si el usuario está en estado NUEVO, forzar cambio de contraseña
        if (strtoupper(trim($user->Estado_Usuario)) === 'NUEVO') {
            // Reiniciar intentos fallidos
            $user->Intentos_Fallidos = 0;
            $user->save();
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->route('password.change.form');
        }

        // 9. Si es primer ingreso, forzar doble verificación OTP
        if ($user->Primer_Ingreso == 1) {
            // Generar y guardar OTP
            $otp = rand(100000, 999999);
            $expiration = now()->addMinutes(10);
            $user->otp_code = $otp;
            $user->otp_expires_at = $expiration;
            // Reiniciar intentos fallidos
            $user->Intentos_Fallidos = 0;
            $user->save();

            \Mail::raw("Tu código de verificación es: $otp", function ($message) use ($user) {
                $message->to($user->Correo_Electronico)
                        ->subject('Código de verificación OTP');
            });

            session(['otp_validated_user' => $user->Usuario]);
            return redirect()->route('otp.form')->with('status', 'Código enviado a tu correo electrónico');
        }

        // 10. Hacer Login solo si ya no es NUEVO ni Primer_Ingreso
        Auth::login($user);
        $request->session()->regenerate();

        // Si el usuario tenía Primer_Ingreso en 1, actualizarlo a 0 tras el primer logeo (solo si no es NUEVO)
        if ($user->Primer_Ingreso == 1 && strtoupper(trim($user->Estado_Usuario)) !== 'NUEVO') {
            $user->Primer_Ingreso = 0;
        }
        // Reiniciar intentos fallidos
        $user->Intentos_Fallidos = 0;
        $user->save();

        //Registrar en la bitacora
>>>>>>> rama-bitacora
        EVENT_BITACORA($user->Id_Usuario, 1, 'Ingreso', 'El usuario ha iniciado sesión.');

        // Redirigir al dashboard 
        return redirect()->intended('/dashboard');
    }

<<<<<<< HEAD
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
=======
    public function showChangePasswordForm()
    {
        return view('Auth.passwords.cambiar-contraseña');
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
>>>>>>> rama-bitacora

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
