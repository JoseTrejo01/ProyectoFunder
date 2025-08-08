<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ahorro extends Model
{
    use HasFactory;

    protected $table = 'tbl_ahorros';

    protected $fillable = [
        'Id_Beneficiario',
        'Id_Organizacion',
        'Monto',
        'Fecha',
    ];

      protected $casts = [
        'Fecha' => 'datetime',
    ];
    public function beneficiario()
    {
        return $this->belongsTo(Beneficiario::class, 'Id_Beneficiario', 'Id_Beneficiario');
}
    

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'Id_Organizacion');
    }
}
