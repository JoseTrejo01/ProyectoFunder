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
    'Id_Beneficiario',
    'Id_Organizacion',
    'Monto',
    'Fecha',
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