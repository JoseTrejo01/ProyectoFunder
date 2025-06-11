<?php
// app/Http/Controllers/Auth/ForgotPasswordController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('Auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Log para depuración
        Log::info('Solicitud de reset password iniciada', [
            'usuario' => $request->Usuario,
            'ip' => $request->ip()
        ]);

        // Validación
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

        // Convertir a mayúsculas
        $request->merge(['Usuario' => strtoupper($request->Usuario)]);

        if ($validator->fails()) {
            Log::warning('Validación fallida para reset password', [
                'usuario' => $request->Usuario,
                'errores' => $validator->errors()
            ]);
            return back()->withErrors($validator)->withInput();
        }

        // Buscar usuario
        $user = User::where('Usuario', $request->Usuario)->first();

        if (!$user) {
            Log::warning('Usuario no encontrado', ['usuario' => $request->Usuario]);
            return back()->withErrors(['Usuario' => 'El usuario no existe.']);
        }

        // Verificar que el usuario tenga email
        if (empty($user->Correo_Electronico)) {
            Log::warning('Usuario sin correo electrónico', [
                'usuario' => $request->Usuario,
                'id_usuario' => $user->Id_Usuario
            ]);
            return back()->withErrors(['Usuario' => 'Este usuario no tiene un correo electrónico registrado.']);
        }

        // Verificar que el usuario esté activo
        if (strtoupper(trim($user->Estado_Usuario)) !== 'ACTIVO') {
    Log::warning('Usuario inactivo intenta reset', [
        'usuario' => $request->Usuario,
        'estado' => $user->Estado_Usuario
    ]);
    return back()->withErrors(['Usuario' => 'Esta cuenta está desactivada.']);
}


        try {
            // Generar token
                      $token = app('auth.password.broker')->createToken($user);
            
            Log::info('Token generado para reset password', [
                'usuario' => $user->Usuario,
                'email' => $user->Correo_Electronico,
                'token_length' => strlen($token)
            ]);

            // Enviar notificación
            $user->sendPasswordResetNotification($token);
            
            Log::info('Correo de reset enviado exitosamente', [
                'usuario' => $user->Usuario,
                'email' => $user->Correo_Electronico
            ]);

            return back()->with('status', 'Hemos enviado un enlace de restablecimiento a tu correo electrónico registrado: ' . $user->Correo_Electronico);

        } catch (\Exception $e) {
            Log::error('Error enviando email de recuperación', [
                'usuario' => $user->Usuario,
                'email' => $user->Correo_Electronico,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['Usuario' => 'Error al enviar el correo. Por favor, intenta nuevamente o contacta al administrador.']);
        }
    }
}
