<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluacionActualizada extends Model
{
    protected $table = 'tbl_evaluacion_actualizada';

    protected $fillable = [
        'evaluacion_id',
        'organizacion_id',
        'total_organizacion',
        'total_gestion',
        'total_componentes',
        'desempeno_institucional',
        'eficiencia_financiera',
        'apalancamiento_financiero',
        'sostenibilidad_financiera',
        'calidad_cartera',
        'total_financiero',
        'calificacion_total',
        'categoria',
    ];
       public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'organizacion_id', 'Id_Organizacion');
    }

   public function evaluacion()
{
    return $this->belongsTo(Evaluacion::class, 'evaluacion_id', 'Id_Evaluacion');
}
}
