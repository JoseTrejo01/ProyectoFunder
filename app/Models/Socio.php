<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    protected $table = 'tbl_beneficiario';
    protected $primaryKey = 'Id_Beneficiario';

    protected $fillable = [
        'Id_Organizacion',
        'Nombre_Beneficiario',
        'DNI',
        'Nombre_Caja',
        'genero',
        'fecha_nacimiento',
        'edad',
        'estado_civil',
        'etnia',
        'nivel_educativo',
        'medio_comunicacion',
        'departamento',
        'municipio',
        'comunidad',
        'direccion',
        'Telefono',
        'actividad_economica',
        'actividad_no_agricola',
        'Tipo_Cargo',
        'Tipo_De_Socio',
        'categoria',
        'estado'
    ];

    public function actividades()
{
    return $this->hasMany(ActividadEconomica::class, 'Id_Beneficiario', 'Id_Beneficiario');
}
}
