<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    protected $table = 'tbl_beneficiario';
    protected $primaryKey = 'Id_Beneficiario';
    public $timestamps = true;

    protected $fillable = [
        'Id_Organizacion',
        'Nombre_Beneficiario',
        'DNI',
        'genero',
        'fecha_nacimiento',
        'Telefono',
        'direccion',
        'actividad_economica',
        'Tipo_Cargo',
        'Tipo_De_Socio',
        'estado',
    ];
}
