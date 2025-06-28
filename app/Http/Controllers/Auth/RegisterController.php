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

