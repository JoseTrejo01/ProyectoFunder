<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'tbl_evaluacion'; // <--- Aquí defines el nombre real de la tabla
    protected $primaryKey = 'Id_Evaluacion';
    public $incrementing = true;
    protected $keyType = 'int';
  protected $fillable = [
    'organizacion_id',
    'mayor_seis_meses',
    'personeria_juridica',
    'personeria_tramite',
    'rtn',
    'frecuencia_reunion',
    'actas_sesion',
    'plan_trabajo',
    'estatutos',
    'aplican_estatutos',
    'libros_contables',
    'informes_financieros',
    'actas_credito',
    'gestion_reglamento',
    'actas_fiscalizadora',
    'informes_fiscalizadora',
    'libro_prestamos',
    'libro_ahorros',
    'libro_caja',
    'libro_aportaciones',
    'libro_ahorros_prestamos',
    'libros_actas',
    'formulario_solicitud',
    'exigencia_garantias',
    'dictamen_credito',
    'pagare',
    'letra_cambio',
    'eficiencia_financiera',
    'apalancamiento_financiero',
    'apalancamiento',
    'sostenibilidad',
    'sostenibilidad_financiera',
    'calidad_cartera',
    'total_organizacion',
    'total_gestion',
    'total_financiero',
    'desempeno_institucional',
    'calificacion_total',
    'categoria',
    'total_componentes'
];


    public function actualizada()
{
    return $this->hasOne(EvaluacionActualizada::class, 'evaluacion_id', 'Id_Evaluacion');
}
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'organizacion_id', 'Id_Organizacion');
    }

   public function getPorcentajeInstitucionalAttribute()
{
    // Verificamos que ninguno esté null para evitar errores
    if (is_null($this->total_organizacion) || is_null($this->total_gestion) || is_null($this->total_componentes)) {
        return 0;
    }

    return round(($this->total_organizacion + $this->total_gestion) / 3, 2);
}
public function getPorcentajeFinancieroAttribute() {
    return round(($this->apalancamiento_financiero + $this->sostenibilidad_financiera) / 2, 2);
}

public function getCalificacionTotalPctAttribute() {
    return round(($this->porcentaje_institucional + $this->porcentaje_financiero) / 2, 2);
}

public function getCategoriaCalculadaAttribute() {
    $total = $this->calificacion_total_pct;
    return match(true) {
        $total >= 90 => 'A',
        $total >= 70 => 'B',
        $total >= 50 => 'C',
        default => 'D',
    };
}

}
