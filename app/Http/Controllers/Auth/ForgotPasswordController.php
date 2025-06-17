<?php
// app/Http/Controllers/Auth/ForgotPasswordController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    //Funcion que retorna la vista del reseteo de la contraseña
    public function showLinkRequestForm()
    {
        return view('Auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
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
            return back()->withErrors($validator)->withInput();
        }

        // Buscar usuario
        $user = User::where('Usuario', $request->Usuario)->first();

        if (!$user) {
            return back()->withErrors(['Usuario' => 'El usuario no existe.']);
        }

        // Verificar que el usuario esté activo
        if (strtoupper(trim($user->Estado_Usuario)) !== 'ACTIVO') {
            return back()->withErrors(['Usuario' => 'Esta cuenta está desactivada.']);
        }

        try {
            // Generar token
            $token = app('auth.password.broker')->createToken($user);

            // Enviar notificación
            $user->sendPasswordResetNotification($token);

            return back()->with('status', 'Hemos enviado un enlace de restablecimiento a tu correo electrónico registrado: ' . $user->Correo_Electronico);

        } catch (\Exception $e) {
            return back()->withErrors(['Usuario' => 'Error al enviar el correo. Por favor, intenta nuevamente o contacta al administrador.']);
        }
    }
}
