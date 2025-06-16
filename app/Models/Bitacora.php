<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    // Especifica el nombre de la tabla si no sigue la convención
    protected $table = 'tbl_ms_bitacora';

    // Indica que no usará timestamps automáticos
    public $timestamps = false;

    // Define la clave primaria si no es 'id'
    protected $primaryKey = 'Id_Bitacora';

    // Campos que pueden asignarse masivamente
    protected $fillable = [
        'Id_Usuario',
        'Id_Objeto',
        'Fecha',
        'Accion',
        'Descripcion'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'Id_Usuario', 'Id_Usuario');
    }

    public function objeto()
    {
        return $this->belongsTo(Objeto::class, 'Id_Objeto', 'Id_Objeto');
    }
}
