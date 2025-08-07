<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Objeto extends Model
{
    protected $table = 'tbl_ms_objeto';
    protected $primaryKey = 'Id_Objeto';
    public $timestamps = false;

    protected $fillable = [
        'Objeto', 'Descripcion', 'Tipo_Objeto', 'Estado', 'Creado_Por', 'Fecha_Creacion', 'Modificado_Por', 'Fecha_Modificacion'
    ];
}
