<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'tbl_departamento'; // usa el nombre real de tu tabla
    protected $primaryKey = 'Id_Departamento';
    public $timestamps = false;

    protected $fillable = [
        'Nombre_Departamento',
    ];

    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'Id_Departamento');
    }
}
