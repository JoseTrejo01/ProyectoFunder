<?php
// app/Http/Controllers/Auth/ResetPasswordController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\PasswordHistory;
use Illuminate\Support\Facades\Log;

   

class ResetPasswordController extends Controller
{
    /**
     * Mostrar el formulario de reset de contraseña
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('Auth.passwords.reset')->with([
            'token' => $token,
            'email' => $request->Correo_Electronico
        ]);
    }

    /**
     * Procesar el reset de contraseña
     */
    public function reset(Request $request)
    {
        // Validación
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'Correo_Electronico' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'token.required' => 'Token requerido.',
            'Correo_Electronico.required' => 'El correo electrónico es requerido.',
            'Correo_Electronico.email' => 'Formato de correo inválido.',
            'password.required' => 'La contraseña es requerida.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Intentar resetear la contraseña
            $response = Password::reset(
                $request->only('Correo_Electronico', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    // Guardar la contraseña actual en el historial
                    PasswordHistory::create([
                        'Id_Usuario' => $user->Id_Usuario,
                        'Contraseña' => Hash::make($password),
                        'Fecha_Creacion' => now(),
                        'Creado_Por' => 'system',
                    ]);

                    // Registrar en bitácora
                    EVENT_BITACORA(
                        $user->Id_Usuario,
                        1, // Cambia por el Id_Objeto correspondiente a "Usuarios" o "Seguridad"
                        'Upadate',
                        'El usuario reseteó su contraseña.'
                    );

                    // Llamar a la función para actualizar la nueva contraseña
                    $this->resetPassword($user, $password);
                }
            );

            if ($response == Password::PASSWORD_RESET) {
                Log::info('Contraseña reseteada exitosamente', [
                    'email' => $request->Correo_Electronico
                ]);

                return redirect()->route('login')->with('status', 'Tu contraseña ha sido restablecida exitosamente.');
            } else {
                Log::warning('Error al resetear contraseña', [
                    'email' => $request->Correo_Electronico,
                    'response' => $response
                ]);

                return back()->withInput($request->only('Correo_Electronico'))
                    ->withErrors(['Correo_Electronico' => $this->getErrorMessage($response)]);
            }

        } catch (\Exception $e) {
            Log::error('Excepción al resetear contraseña', [
                'Correo_Electronico' => $request->Correo_Electronico,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput($request->only('Correo_Electronico'))
                ->withErrors(['Correo_Electronico' => 'Error interno. Intenta nuevamente.']);
        }
    }

    /**
     * Resetear la contraseña del usuario
     */
    protected function resetPassword($user, $password)
    {
        // Actualizar la contraseña en tu tabla personalizada
        $user->update([
            'Contraseña' => Hash::make($password),
            'Primer_Ingreso' => 0, // Opcional: marcar que ya no es primer ingreso
            'Modificado_Por' => 'SISTEMA',
            'Fecha_Modificacion' => now(),
        ]);

        Log::info('Contraseña actualizada en BD', [
            'usuario' => $user->Usuario,
            'id_usuario' => $user->Id_Usuario
        ]);
    }

     /**
     * Obtener mensaje de error personalizado
     */
    protected function getErrorMessage($response)
    {
        switch ($response) {
            case Password::INVALID_TOKEN:
                return 'El enlace de restablecimiento ha expirado o es inválido.';
            case Password::INVALID_USER:
                return 'No encontramos un usuario con esa dirección de correo electrónico.';
            default:
                return 'Error al restablecer la contraseña. Intenta nuevamente.';
        }
    }
}
