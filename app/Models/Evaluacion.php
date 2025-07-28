<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'tbl_evaluaciones';

    protected $fillable = [
        'id_organizacion',
        'criterio_id',
        'puntuacion_inicial',
        'puntuacion_actualizada',
        'peso',
        'ponderacion_inicial',
        'ponderacion_actual',
    ];

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion', 'Id_Organizacion');
    }

    public function criterio()
    {
        return $this->belongsTo(Criterio::class);
    }

    
}