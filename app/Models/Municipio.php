<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'tbl_municipio'; // nombre de tu tabla
    protected $primaryKey = 'Id_Municipio'; // clave primaria personalizada
    public $timestamps = false; // desactiva timestamps si no usas created_at / updated_at

    protected $fillable = [
        'Id_Departamento',
        'Nombre_Municipio',
    ];

    // Si quieres establecer relación con el modelo Emprendimiento:
    public function emprendimientos()
    {
        return $this->hasMany(Emprendimiento::class, 'Id_Municipio');
    }
}
