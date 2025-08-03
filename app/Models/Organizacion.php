<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organizacion extends Model
{
    protected $table = 'tbl_organizacion';
    protected $primaryKey = 'Id_Organizacion';
    public $timestamps = false;

    protected $fillable = [
        'Id_Aldea',
        'Id_Usuario',
        'Nombre_Organizacion',
        'Estado_Organizacion',
    ];

    public function prestamos()
    {
    return $this->hasMany(Prestamo::class, 'socio_id', 'Id_Organizacion');
    }
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'id_organizacion', 'Id_Organizacion');
    }
    public function aldea()
{
    return $this->belongsTo(Aldea::class, 'Id_Aldea', 'Id_Aldea');
}

}
