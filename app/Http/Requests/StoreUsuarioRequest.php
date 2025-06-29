<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return  true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'Usuario' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Z0-9]+$/', // Solo letras mayúsculas y números, sin espacios ni símbolos
                'unique:tbl_ms_usuario,Usuario'
            ],
            'Nombre_Usuario' => [
                'required',
                'string',
                'max:100',
                'regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u' // Solo letras mayúsculas y espacios
            ],
            'Correo_Electronico' => 'required|email|max:100|unique:tbl_ms_usuario,Correo_Electronico',
            'Contraseña' => [
                'required',
                'string',
                'size:8' // exactamente 8 caracteres
            ],
            'Id_Rol' => 'required|integer|exists:tbl_roles,Id_Rol',
            'Estado_Usuario' => 'required|in:NUEVO,ACTIVO,BLOQUEADO,INACTIVO,VACACIONES',
        ];
    }

        public function messages()
    {
        return [
            'Usuario.unique' => 'Este nombre de usuario ya existe.',
            'Correo_Electronico.unique' => 'Este correo ya está registrado.',
            'Correo_Electronico.email' => 'Debe ingresar un correo válido con @.',
            'Id_Rol.required' => 'Debe seleccionar un rol.',
            'Estado_Usuario.in' => 'Estado no válido.',
            'Usuario.regex' => 'El usuario solo debe contener letras mayúsculas y números (sin espacios ni símbolos).',
            'Nombre_Usuario.regex' => 'El nombre solo debe contener letras mayúsculas y espacios.',
            'Contraseña.size' => 'La contraseña debe contener exactamente 8 caracteres.',
        ];
    }
}
