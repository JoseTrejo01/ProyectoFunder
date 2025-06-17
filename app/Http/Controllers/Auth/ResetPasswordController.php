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

class ResetPasswordController extends Controller
{
    //Funcion que muestra el formulario de reset de contraseña

    public function showResetForm(Request $request, $token = null)
    {
        return view('Auth.passwords.reset')->with([
            'token' => $token,
            'email' => $request->Correo_Electronico
        ]);
    }

    //Funcion para procesar el reset de la contraseña
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

                    // Registrar en bitácora cuando el usuario cambia contraseña
                    EVENT_BITACORA(
                        $user->Id_Usuario,
                        1, 
                        'Upadate',
                        'El usuario reseteó su contraseña.'
                    );

                    //Aqui llamamos a la función para actualizar la nueva contraseña
                    $this->resetPassword($user, $password);
                }
            );

            if ($response == Password::PASSWORD_RESET) {
                return redirect()->route('login')->with('status', 'Tu contraseña ha sido restablecida exitosamente.');
            } else {
                return back()->withInput($request->only('Correo_Electronico'))
                    ->withErrors(['Correo_Electronico' => $this->getErrorMessage($response)]);
            }

        } catch (\Exception $e) {
            return back()->withInput($request->only('Correo_Electronico'))
                ->withErrors(['Correo_Electronico' => 'Error interno. Intenta nuevamente.']);
        }
    }

    //Esta funcion actualiza en la tabla usuario la contraseña
    protected function resetPassword($user, $password)
    {
        
        $user->update([
            'Contraseña' => Hash::make($password),
            'Primer_Ingreso' => 0,
            'Modificado_Por' => 'SISTEMA',
            'Fecha_Modificacion' => now(),
        ]);
    }

   
     // Aqui es para Obtener mensaje de error personalizado
  
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
