<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\PasswordHistory;

class ResetPasswordController extends Controller
{
    // Mostrar el formulario de restablecimiento de contraseña (vía OTP)
    public function showResetForm()
    {
        if (!session('otp_validated_user')) {
            return redirect()->route('otp.form')->withErrors(['otp' => 'Primero debes verificar el código OTP.']);
        }

        $user = User::where('Usuario', session('otp_validated_user'))->first();

        return view('auth.passwords.reset', [
            'email' => $user->Correo_Electronico
        ]);
    }

    // Procesar el cambio de contraseña sin token (usando OTP)
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Correo_Electronico' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'Correo_Electronico.required' => 'El correo electrónico es requerido.',
            'Correo_Electronico.email' => 'Formato de correo inválido.',
            'password.required' => 'La contraseña es requerida.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('Correo_Electronico', $request->Correo_Electronico)->first();

        if (!$user || session('otp_validated_user') !== $user->Usuario) {
            return redirect()->route('login')->withErrors([
                'Correo_Electronico' => 'No autorizado para cambiar la contraseña.'
            ]);
        }

        // Guardar la contraseña en el historial
        PasswordHistory::create([
            'Id_Usuario' => $user->Id_Usuario,
            'Contraseña' => Hash::make($request->password),
            'Fecha_Creacion' => now(),
            'Creado_Por' => 'system',
        ]);

        // Actualizar la contraseña
        $user->update([
            'Contraseña' => Hash::make($request->password),
            'Primer_Ingreso' => 0,
            'Modificado_Por' => 'SISTEMA',
            'Fecha_Modificacion' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        // Registrar en la bitácora
        EVENT_BITACORA(
            $user->Id_Usuario,
            1,
            'Update',
            'El usuario reseteó su contraseña por OTP.'
        );

        session()->forget('otp_validated_user');

        return redirect()->route('login')->with('status', 'Tu contraseña ha sido restablecida exitosamente.');
    }
}
