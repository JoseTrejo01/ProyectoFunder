<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ahorro extends Model
{
    use HasFactory;

    protected $table = 'ahorros';

    protected $fillable = [
        'id_organizacion',
        'id_beneficiario',
        'monto_ahorrado',
    ];

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion', 'Id_Organizacion');
    }

    public function beneficiario()
    {
        return $this->belongsTo(Socio::class, 'id_beneficiario', 'Id_Beneficiario');
    }
}
