<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ahorro extends Model
{
    use HasFactory;

    protected $table = 'tbl_ahorros';
    protected $primaryKey = 'id_Ahorro';
    public $timestamps = false;

    protected $fillable = [
        'Id_Beneficiario',
        'Monto',
        'Fecha',
    ];

    /**
     * Define la relación con el modelo Beneficiario.
     * Un ahorro pertenece a un beneficiario.
     */
    public function beneficiario()
    {
        return $this->belongsTo(Beneficiario::class, 'Id_Beneficiario', 'id_Beneficiario');
    }

    /**
     * Define la relación con el modelo Organizacion.
     * Un ahorro pertenece a una organización, a través de su beneficiario.
     * Esta relación es útil para las consultas en el controlador.
     */
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'Id_Organizacion', 'Id_Organizacion');
    }
}