<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('Auth.register');
    }

public function register(Request $request)
{
    // Validar los datos
    $this->validator($request->all())->validate();

    // Crear el usuario
    $user = $this->create($request->all());

    // Enviar el correo de verificación
    event(new Registered($user));

    // Iniciar sesión al usuario automáticamente (necesario para poder acceder a /email/verify)
    Auth::login($user);

    // Redirigir a la vista de verificación
    return redirect()->route('verification.notice')->with('status', 'verification-link-sent');
}
   protected function validator(array $data)
{
   return Validator::make($data, [
        'Usuario' => ['required', 'string', 'max:30', 'unique:tbl_ms_usuario'],
        'Nombre_Usuario' => ['required', 'string', 'max:100'],
        'Correo_Electronico' => ['required', 'string', 'email', 'max:60', 'unique:tbl_ms_usuario'],
        'Contraseña' => ['required', 'string', 'size:8', 'confirmed', 'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/'],
    ], [
        'Usuario.required' => 'El campo usuario es obligatorio',
        'Nombre_Usuario.required' => 'El campo nombre de usuario es obligatorio',
        'Correo_Electronico.required' => 'El campo correo electrónico es obligatorio',
        'Usuario.max' => 'El usuario no puede tener más de 30 caracteres.',
        'Nombre_Usuario.max' => 'El nombre de usuario no puede tener más de 100 caracteres.',
        'Correo_Electronico.max' => 'El correo electrónico no puede tener más de 60 caracteres.',
        'Usuario.unique' => 'El usuario ya está registrado.',
        'Correo_Electronico.unique' => 'El correo electrónico ya está registrado.',
        'Contraseña.required' => 'El campo contraseña es obligatorio',
        'Contraseña.size' => 'La contraseña debe tener al menos 8 caracteres.',
        'Contraseña.confirmed' => 'La confirmación de la contraseña no coincide.',
        'Contraseña.regex' => 'La contraseña debe contener letras y números sin espacios.'
    ]);
}


    protected function create(array $data)
    {
        // Obtener el valor de ADMIN_DIAS_VIGENCIA desde tbl_parametros
        $diasVigencia = DB::table('tbl_parametros')
            ->where('Nombre_Parametro', 'ADMIN_DIAS_VIGENCIA')
            ->value('Valor');

        // Asegurarse de que diasVigencia sea un número
        $diasVigencia = (int) $diasVigencia;

        // Fecha de creación
        $fechaCreacion = now();
        // La fecha de vencimiento es la fecha de creación + días de vigencia
        $fechaVencimiento = $fechaCreacion->copy()->addDays($diasVigencia);

        return User::create([
            'Id_Rol' => 3, // Rol AUTO-REGISTRO
            'Usuario' => strtoupper($data['Usuario']),
            'Nombre_Usuario' => strtoupper($data['Nombre_Usuario']),
            'Correo_Electronico' => $data['Correo_Electronico'],
            'Contraseña' => Hash::make($data['Contraseña']),
            'Estado_Usuario' => 'NUEVO', 
            'Primer_Ingreso' => 1,
            'Fecha_Creacion' => $fechaCreacion,
            'Fecha_Vencimiento' => $fechaVencimiento,
        ]);
    }
}

