<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ahorro extends Model
{
    use HasFactory;

    protected $table = 'tbl_ahorros';

    // 👇 Usa el nombre REAL de la PK de tu tabla
    protected $primaryKey = 'Id_Ahorro';

    // Pon esto en true SOLO si tu tabla tiene created_at y updated_at
    public $timestamps = false;

    protected $fillable = [
        'Id_Organizacion',
        'Id_Beneficiario',
        'Monto',
        'Fecha',
    ];

    public function beneficiario()
    {
        return $this->belongsTo(Beneficiario::class, 'Id_Beneficiario', 'Id_Beneficiario');
    }

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'Id_Organizacion', 'Id_Organizacion');
    }
}
