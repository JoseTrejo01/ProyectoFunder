<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblMsUsuario extends Model
{
    protected $table = 'tbl_ms_usuario';
    protected $primaryKey = 'Id_Usuario';
    public $timestamps = false;

    protected $fillable = [
        'Id_Usuario',
        'Nombre_Usuario',
    ];
}
