<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'tbl_municipio';
    protected $primaryKey = 'Id_Municipio';
    public $timestamps = false;

    protected $fillable = [
        'Nombre_Municipio',
        'Id_Departamento',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'Id_Departamento', 'Id_Departamento');
    }
}
