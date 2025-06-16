<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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

        // Luego del registro se envia a la vista del login
        return redirect()->route('login')->with('success', 'Registro exitoso. Por favor, inicia sesión con tus credenciales.');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'Usuario' => ['required', 'string', 'max:60', 'unique:tbl_ms_usuario'],
            'Nombre_Usuario' => ['required', 'string', 'max:100'],
            'Correo_Electronico' => ['required', 'string', 'email', 'max:60', 'unique:tbl_ms_usuario'],
            'Contraseña' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/'], // Letras y números
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

        //Aqui se suma la fecha de cracion mas el parametro ADMIN_DIAS_VIGENCIA para insertar la fecha de vencimiento del usuario
        $fechaCreacion = now();
        $fechaVencimiento = $fechaCreacion->copy()->addDays($diasVigencia); // Copia la fecha para no modificar la original

        return User::create([
            'Id_Rol' => 2, // Asigna un rol por defecto
            'Usuario' => strtoupper($data['Usuario']), // Convertir a mayúsculas, es una validacion que piden
             'Nombre_Usuario' => strtoupper($data['Nombre_Usuario']), // Convertir a mayúsculas
            'Correo_Electronico' => $data['Correo_Electronico'],
            'Contraseña' => Hash::make($data['Contraseña']),
            'Estado_Usuario' => 'INACTIVO',
            'Primer_Ingreso' => 1,
            'Fecha_Creacion' => $fechaCreacion,
            'Fecha_Vencimiento' => $fechaVencimiento, // Añadir la fecha de vencimiento
        ]);
    }
}

