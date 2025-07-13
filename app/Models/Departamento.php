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
}
