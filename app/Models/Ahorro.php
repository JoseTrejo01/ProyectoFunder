<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ahorro extends Model
{
    use HasFactory;

    protected $table = 'tbl_ahorros';
    protected $primaryKey = 'Id_Ahorro';

    protected $fillable = [
<<<<<<< HEAD
        'Id_Beneficiario',
        'Id_Organizacion',
        'Monto',
        'Fecha',
=======
        'Id_Organizacion',
        'beneficiario_id',
        'monto',
        'fecha',
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
>>>>>>> e5d6109a3882fb515218b98c5255c80695c7859e
    ];

    public function beneficiario()
    {
        return $this->belongsTo(Beneficiario::class, 'Id_Beneficiario', 'Id_Beneficiario');
    }

    public function organizacion()
    {
<<<<<<< HEAD
        return $this->belongsTo(Organizacion::class, 'Id_Organizacion');
=======
        return $this->belongsTo(Organizacion::class, 'Id_Organizacion', 'Id_Organizacion');
    }

    public function beneficiario()
    {
        return $this->belongsTo(Beneficiario::class, 'beneficiario_id', 'Id_Beneficiario');
>>>>>>> e5d6109a3882fb515218b98c5255c80695c7859e
    }
}
