<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;            // ← IMPORTANTE
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\PasswordHistory;                 // ← IMPORTANTE
use Illuminate\Auth\Events\PasswordReset;      // ← NUEVO (para disparar el evento)
                                              // EVENT_BITACORA es global; no requiere use

class ResetPasswordController extends Controller
{
    // Mostrar el formulario de restablecimiento de contraseña (vía OTP)
    public function showResetForm()
    {
        if (!session('otp_validated_user')) {
            return redirect()->route('otp.form')
                ->withErrors(['otp' => 'Primero debes verificar el código OTP.']);
        }

        $user = User::where('Usuario', session('otp_validated_user'))->first();

        return view('Auth.passwords.reset', [
            'email' => $user?->Correo_Electronico,
        ]);
    }

    // Procesar el cambio de contraseña sin token (usando OTP)
    public function reset(Request $request)
    {
        // Validación: exactamente 8 y sin espacios (ajusta a min:8 si lo prefieres)
        $request->validate([
            'Correo_Electronico' => ['required', 'email'],
            'password' => [
                'required',
                'string',
                'size:8',          // exactamente 8
                'regex:/^\S*$/u',  // sin espacios
                'confirmed',
            ],
        ], [
            'Correo_Electronico.required' => 'El correo electrónico es requerido.',
            'Correo_Electronico.email'    => 'Formato de correo inválido.',
            'password.required'           => 'La contraseña es requerida.',
            'password.size'               => 'La contraseña debe tener exactamente 8 caracteres.',
            'password.regex'              => 'La contraseña no puede contener espacios.',
            'password.confirmed'          => 'Las contraseñas no coinciden.',
        ]);

        $user = User::where('Correo_Electronico', $request->Correo_Electronico)->first();

        // Asegura que el OTP validado corresponde al usuario
        if (!$user || session('otp_validated_user') !== $user->Usuario) {
            return redirect()->route('login')->withErrors([
                'Correo_Electronico' => 'No autorizado para cambiar la contraseña.'
            ]);
        }

        // ==== Validación de NO-REUSO (últimas 5) con soporte a legacy (texto plano) ====
        $plain = $request->password;

        $looksHashed = function (string $v): bool {
            return str_starts_with($v, '$2y$') || str_starts_with($v, '$argon2');
        };

        // Últimas 5 del historial
        $ultimosHashes = PasswordHistory::where('Id_Usuario', $user->Id_Usuario)
            ->orderByDesc('Fecha_Creacion')
            ->limit(5)
            ->pluck('Contraseña');

        // Incluir la contraseña actual por si no está en historial
        if (!empty($user->Contraseña)) {
            $ultimosHashes->push($user->Contraseña);
        }

        foreach ($ultimosHashes as $old) {
            if ($looksHashed($old)) {
                if (Hash::check($plain, $old)) {
                    return back()->withErrors([
                        'password' => 'No puedes reutilizar tus últimas 5 contraseñas.'
                    ])->withInput();
                }
            } else {
                // Legacy: guardada como texto plano en la BD
                if (hash_equals($plain, (string) $old)) {
                    return back()->withErrors([
                        'password' => 'No puedes reutilizar tus últimas 5 contraseñas.'
                    ])->withInput();
                }
            }
        }
        // ==== Fin validación no-reuso ====

        try {
            DB::transaction(function () use ($user, $plain) {
                // Registrar historial SIEMPRE con hash
                PasswordHistory::create([
                    'Id_Usuario'     => $user->Id_Usuario,
                    'Contraseña'     => Hash::make($plain),
                    'Fecha_Creacion' => now(),
                    'Creado_Por'     => 'SISTEMA',
                ]);

                // Actualizar usuario con hash
                $user->update([
                    'Contraseña'         => Hash::make($plain),
                    'Primer_Ingreso'     => 0,
                    'Estado_Usuario'     => 'ACTIVO',
                    'Intentos_Fallidos'  => 0,
                    'otp_code'           => null,
                    'otp_expires_at'     => null,
                    'Modificado_Por'     => 'SISTEMA',
                    'Fecha_Modificacion' => now(),
                ]);
            });

        } catch (ValidationException $ve) {
            return back()->withErrors($ve->errors())->withInput();

        } catch (\Throwable $e) {
            // Puedes loguear $e->getMessage() si deseas
            return back()->withErrors([
                'password' => 'No se pudo actualizar la contraseña. Intenta nuevamente.'
            ])->withInput();
        }

        // ← NUEVO: Disparar evento estándar de Laravel para que el listener registre en bitácora
        event(new PasswordReset($user));

        // ← NUEVO: Registro inmediato y explícito en tu bitácora (además del listener)
        EVENT_BITACORA(
            $user->Id_Usuario,
            'Usuarios',
            'Actualización',
            "El usuario {$user->Usuario} cambió su contraseña manualmente"
        );

        // Limpiar variables del flujo OTP
        session()->forget(['otp_validated_user', 'otp_pending_user']);

        // === COMPORTAMIENTO ACTUAL: autologin y al dashboard ===
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/dashboard')
            ->with('status', 'Contraseña restablecida y sesión iniciada.');

        /* 
        // === OPCIONAL: si prefieres redirigir al login SIN iniciar sesión, usa esto en lugar de lo anterior ===
        // session()->flash('status', 'Contraseña restablecida. Inicia sesión con tu nueva contraseña.');
        // return redirect()->route('login');
        */
    }
}
