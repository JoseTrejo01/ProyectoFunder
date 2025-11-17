<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ahorro extends Model
{
    use HasFactory;

    protected $table = 'tbl_ahorros';
    protected $primaryKey = 'id_Ahorro'; // 
    public $timestamps = false;

    protected $fillable = [
        'Id_Organizacion',   
        'Id_Beneficiario',
        'Monto',
        'Fecha',
    ];

    /**
     * Relación con Beneficiario:
     * Un ahorro pertenece a un beneficiario.
     */
    public function beneficiario()
    {
        return $this->belongsTo(Beneficiario::class, 'Id_Beneficiario', 'Id_Beneficiario');
    }

    /**
     * Relación con Organizacion:
     * Un ahorro pertenece a una organización.
     */
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'Id_Organizacion', 'Id_Organizacion');
    }
}
