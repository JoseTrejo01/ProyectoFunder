<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $table = 'tbl_ms_bitacora';
    public $timestamps = false;
    protected $primaryKey = 'Id_Bitacora';

    protected $fillable = [
        'Id_Usuario',
        'Nombre_Usuario', // Asegúrate que así se llama tu campo en la base de datos
        'Id_Objeto',
        'Fecha',
        'Accion',
        'Descripcion'
    ];

    public function usuario()
    {
        return $this->belongsTo(\App\Models\TblMsUsuario::class, 'Id_Usuario', 'Id_Usuario');
    }

    public function objeto()
    {
        return $this->belongsTo(Objeto::class, 'Id_Objeto', 'Id_Objeto');
    }
    protected $casts = ['Fecha' => 'datetime'];
    
public function getFechaLocalAttribute()
{
    return optional($this->Fecha)->timezone(config('app.timezone'));
}

}
