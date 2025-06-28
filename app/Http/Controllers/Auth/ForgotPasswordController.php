<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // Mostrar vista para ingresar el usuario
    public function showLinkRequestForm()
    {
        return view('Auth.passwords.email');
    }

    // Enviar el OTP al correo
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Usuario' => [
                'required',
                'string',
                'regex:/^\S*$/u',
                'exists:tbl_ms_usuario,Usuario'
            ]
        ], [
            'Usuario.required' => 'El campo usuario es obligatorio.',
            'Usuario.regex' => 'No se permiten espacios en blanco.',
            'Usuario.exists' => 'El usuario no existe en nuestros registros.'
        ]);

        $request->merge(['Usuario' => strtoupper($request->Usuario)]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('Usuario', $request->Usuario)->first();

        if (!$user) {
            return back()->withErrors(['Usuario' => 'El usuario no existe.']);
        }

        if (strtoupper(trim($user->Estado_Usuario)) !== 'ACTIVO') {
            return back()->withErrors(['Usuario' => 'Esta cuenta está desactivada.']);
        }

        try {
            $otp = rand(100000, 999999);
            $expiration = now()->addMinutes(10);

            $user->otp_code = $otp;
            $user->otp_expires_at = $expiration;
            $user->save();

            \Mail::raw("Tu código de verificación es: $otp", function ($message) use ($user) {
                $message->to($user->Correo_Electronico)
                        ->subject('Código de verificación OTP');
            });

            return redirect()->route('otp.form')->with('status', 'Código enviado a tu correo electrónico');

        } catch (\Exception $e) {
            return back()->withErrors(['Usuario' => 'Hubo un error al enviar el código. Intenta de nuevo.']);
        }
    }

    // Mostrar vista para ingresar el código OTP
    public function showOtpForm()
    {
        return view('Auth.passwords.verify-otp');
    }

    // Verificar OTP ingresado
    public function verifyOtp(Request $request)
    {
        $request->validate([
    'otp' => 'required|digits:6'
], [
    'otp.required' => 'El código OTP es obligatorio.',
    'otp.digits' => 'El código debe tener exactamente 6 dígitos.'
]);
        $user = User::where('otp_code', $request->otp)
                    ->where('otp_expires_at', '>=', now())
                    ->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'El código es inválido o ha expirado.']);
        }

        // Si es primer ingreso, loguear, actualizar Primer_Ingreso y redirigir al dashboard
        if ($user->Primer_Ingreso == 1) {
            \Auth::login($user);
            $user->Primer_Ingreso = 0;
            $user->save();
            session()->forget('otp_validated_user');
            return redirect()->intended('/dashboard');
        }

        // Si es recuperación, flujo normal
        session(['otp_validated_user' => $user->Usuario]);
        return redirect()->route('password.reset.form');
    }

    // Mostrar vista para ingresar nueva contraseña
    public function showResetPasswordForm()
    {
        if (!session('otp_validated_user')) {
            return redirect()->route('otp.form')->withErrors(['otp' => 'Primero debes verificar el código OTP.']);
        }

        $user = User::where('Usuario', session('otp_validated_user'))->first();

        return view('Auth.passwords.reset', ['email' => $user->Correo_Electronico]);
    }

    // Procesar restablecimiento de contraseña
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::where('Correo_Electronico', $request->email)->first();

        if (!$user || session('otp_validated_user') !== $user->Usuario) {
            return redirect()->route('login')->withErrors(['email' => 'No autorizado para cambiar la contraseña.']);
        }

        $user->password = bcrypt($request->password);
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        session()->forget('otp_validated_user');

        return redirect()->route('login')->with('status', 'Contraseña restablecida con éxito.');
    }

    // Reenviar OTP
    public function resendOtp()
    {
        if (!session('otp_validated_user')) {
            return redirect()->route('password.request')->withErrors(['Usuario' => 'Primero debes ingresar tu usuario.']);
        }

        $user = User::where('Usuario', session('otp_validated_user'))->first();

        if (!$user) {
            return redirect()->route('password.request')->withErrors(['Usuario' => 'El usuario no existe.']);
        }

        $otp = rand(100000, 999999);
        $expiration = now()->addMinutes(10);

        $user->otp_code = $otp;
        $user->otp_expires_at = $expiration;
        $user->save();

        \Mail::raw("Tu nuevo código OTP es: $otp", function ($message) use ($user) {
            $message->to($user->Correo_Electronico)
                    ->subject('Nuevo código OTP');
        });

        return back()->with('status', 'Código reenviado a tu correo.');
    }
}
