<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicadorGenero extends Model
{
    use HasFactory;

    protected $table = 'indicador_generos';

    protected $fillable = [
        'nombre_caja_rural',
        'departamento',
        'municipio',
        'comunidad',
        'nombre_apellidos',
        'sexo',           
        'etnia',
        'fecha_nacimiento',
        'edad',
        'identidad',
        'cargo',
    ];
}
