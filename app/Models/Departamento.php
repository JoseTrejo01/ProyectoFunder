<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'tbl_departamento';
    protected $primaryKey = 'Id_Departamento';
    public $timestamps = false;

    protected $fillable = [
        'Nombre_Departamento',
    ];

    // Relación con municipios (un departamento tiene muchos municipios)
    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'Id_Departamento');
    }
}
