<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolesObjeto extends Model
{
    protected $table = 'tbl_ms_roles_objeto';
    protected $primaryKey = 'Id_Roles_Objeto';
    public $timestamps = false;

    protected $fillable = [
        'Id_Rol', 'Id_Objeto', 'Permiso_Insercion', 'Permiso_Eliminacion', 'Permiso_Actualizacion', 'Permiso_Consultar'
    ];

    public function objeto()
    {
        return $this->belongsTo(Objeto::class, 'Id_Objeto', 'Id_Objeto');
    }
}
