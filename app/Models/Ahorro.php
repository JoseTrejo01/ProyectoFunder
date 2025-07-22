<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ahorro extends Model
{
    use HasFactory;

    protected $table = 'tbl_ahorros';

    protected $fillable = [
        'Id_Organizacion',
        'nombre_caja_rural',
        'socios_no',
        'socios_ahorros',
        'socios_promedio',
        'adultos_no',
        'adultos_ahorros',
        'adultos_promedio',
        'ninos_no',
        'ninos_ahorros',
        'ninos_promedio',
        'subtotal_no_socios_no',
        'subtotal_no_socios_ahorros',
        'subtotal_no_socios_promedio',
        'total_no',
        'total_ahorros',
        'total_promedio',
        'beneficiario_id',
        'monto',
        'fecha',
    ];

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'Id_Organizacion');
    }
    public function beneficiario()
{
    return $this->belongsTo(Beneficiario::class, 'beneficiario_id', 'Id_Beneficiario');
}
}
