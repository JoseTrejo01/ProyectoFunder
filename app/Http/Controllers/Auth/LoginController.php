<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Parametro;
use App\Models\Objeto;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('Auth.login');
    }

    public function login(Request $request)
    {
<<<<<<< HEAD
        // 1. Validación
=======
>>>>>>> 8ad241892a49651188d0907be69562e2560963aa
        $validator = Validator::make($request->all(), [
            'Usuario' => ['required', 'string', 'max:30'],
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

        $usuario = strtoupper($request->Usuario);
        $user = User::where('Usuario', $usuario)->first();

        if (!$user) {
            return back()->withErrors(['Usuario' => 'Usuario/contraseña inválidos'])->withInput();
        }

<<<<<<< HEAD
        // 2. Parámetro de intentos fallidos
        $limiteIntentos = Parametro::where('Nombre_Parametro', 'ADMIN_INTENTOS_INVALIDOS')->value('Valor') ?? 3;

        // 3. Verificaciones de estado
=======
        $parametro = Parametro::where('Nombre_Parametro', 'ADMIN_INTENTOS_INVALIDOS')->first();
        $limiteIntentos = $parametro ? intval($parametro->Valor) : 3;

>>>>>>> 8ad241892a49651188d0907be69562e2560963aa
        if (strtoupper(trim($user->Estado_Usuario)) === 'BLOQUEADO') {
            return back()->withErrors(['Usuario' => 'Tu cuenta ha sido bloqueada por múltiples intentos fallidos.'])->withInput();
        }

        if ($user->Id_Rol == 3) {
<<<<<<< HEAD
            return back()->withErrors(['Usuario' => 'Tu usuario está pendiente de aprobación. Contacta a la administración.'])->withInput();
=======
            return back()->withErrors(['Usuario' => 'Tu usuario está pendiente de aprobación. Por favor, contacta a la administración para ser aceptado.'])->withInput();
>>>>>>> 8ad241892a49651188d0907be69562e2560963aa
        }

        if (!in_array(strtoupper(trim($user->Estado_Usuario)), ['ACTIVO', 'NUEVO'])) {
            return back()->withErrors(['Usuario' => 'El usuario no está activo'])->withInput();
        }

<<<<<<< HEAD
        // 4. Verificación de contraseña
=======
>>>>>>> 8ad241892a49651188d0907be69562e2560963aa
        if (!Hash::check($request->Contraseña, $user->Contraseña)) {
            $user->Intentos_Fallidos = ($user->Intentos_Fallidos ?? 0) + 1;

            if ($user->Intentos_Fallidos >= $limiteIntentos) {
                $user->Estado_Usuario = 'BLOQUEADO';

                try {
                    $objeto = Objeto::where('Objeto', 'Usuarios')->first();
                    if ($objeto) {
                        EVENT_BITACORA(
                            $user->Id_Usuario,
                            $objeto->Id_Objeto,
                            'Bloqueo',
                            'El usuario fue bloqueado por intentos fallidos de inicio de sesión.'
                        );
                    }
                } catch (\Throwable $e) {
                    // Silenciar errores de bitácora para evitar ruptura del flujo
                }
            }

            $user->save();

            return back()->withErrors([
                'Usuario' => $user->Estado_Usuario === 'BLOQUEADO'
                    ? 'Tu cuenta ha sido bloqueada'
                    : 'Usuario/contraseña inválidos'
            ])->withInput();
        }

<<<<<<< HEAD
        // 5. Estado NUEVO: forzar cambio de contraseña
=======
        // Si el usuario está en estado NUEVO, forzar cambio de contraseña
>>>>>>> 8ad241892a49651188d0907be69562e2560963aa
        if (strtoupper(trim($user->Estado_Usuario)) === 'NUEVO') {
            $user->Intentos_Fallidos = 0;
            $user->save();

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('password.change.form');
        }

<<<<<<< HEAD
        // 6. Primer ingreso: OTP obligatorio
        if ($user->Primer_Ingreso == 1) {
            $otp = rand(100000, 999999);
            $expiration = now()->addMinutes(10);

            $user->otp_code = $otp;
            $user->otp_expires_at = $expiration;
=======
        // Si es primer ingreso, forzar verificación OTP
        if ($user->Primer_Ingreso == 1) {
            $otp = rand(100000, 999999);
            $user->otp_code = $otp;
            $user->otp_expires_at = now()->addMinutes(10);
>>>>>>> 8ad241892a49651188d0907be69562e2560963aa
            $user->Intentos_Fallidos = 0;
            $user->save();

            Mail::raw("Tu código de verificación es: $otp", function ($message) use ($user) {
                $message->to($user->Correo_Electronico)
                        ->subject('Código de verificación OTP');
            });

            session(['otp_validated_user' => $user->Usuario]);

            return redirect()->route('otp.form')->with('status', 'Código enviado a tu correo electrónico');
        }

<<<<<<< HEAD
        // 7. Login exitoso
=======
        // Login normal
>>>>>>> 8ad241892a49651188d0907be69562e2560963aa
        Auth::login($user);
        $request->session()->regenerate();

        if ($user->Primer_Ingreso == 1) {
            $user->Primer_Ingreso = 0;
        }

        $user->Intentos_Fallidos = 0;
        $user->save();

        EVENT_BITACORA($user->Id_Usuario, 1, 'Ingreso', 'El usuario ha iniciado sesión.');

        return redirect()->intended('/dashboard');
    }

<<<<<<< HEAD
    public function logout(Request $request)
    {
        EVENT_BITACORA(Auth::user()->Id_Usuario, 2, 'Salida', 'El usuario ha cerrado sesión.');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Sesión cerrada correctamente');
    }

=======
>>>>>>> 8ad241892a49651188d0907be69562e2560963aa
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

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

<<<<<<< HEAD
        return redirect()->route('login')->with('success', 'Contraseña cambiada correctamente. Por favor, inicia sesión.');
=======
        return redirect()->route('login')->with('success', 'Contraseña cambiada correctamente. Por favor, inicia sesión con tu nueva contraseña.');
    }

    public function logout(Request $request)
    {
        EVENT_BITACORA(Auth::user()->Id_Usuario, 2, 'Salida', 'El usuario ha cerrado sesión.');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Sesión cerrada correctamente');
>>>>>>> 8ad241892a49651188d0907be69562e2560963aa
    }
}
