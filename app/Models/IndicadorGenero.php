<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicadorGenero extends Model
{
    use HasFactory;

    protected $table = 'tbl_indicador_genero';

    protected $fillable = [
        'tipo',
        'cantidad',
        'edad_promedio',
        'id_organizacion',
    ];

    // Relación opcional con organizaciones si la necesitas
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion');
    }
}
