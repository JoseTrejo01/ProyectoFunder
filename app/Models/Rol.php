<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'tbl_ms_rol';
    protected $primaryKey = 'Id_Rol';
    public $timestamps = false;

    protected $fillable = [
        'Rol', 'Descripcion', 'Creado_Por', 'Fecha_Creacion', 'Modificado_Por', 'Fecha_Modificacion'
    ];

    public function permisos()
    {
        return $this->hasMany(RolesObjeto::class, 'Id_Rol', 'Id_Rol');
    }
}
