<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ahorro extends Model
{
    use HasFactory;

    protected $table = 'tbl_ahorros';
    protected $primaryKey = 'id'; // ✔️ ESTA ES LA CLAVE REAL
    public $timestamps = true;    // ✔️ Tu tabla sí tiene created_at / updated_at

    protected $fillable = [
        'Id_Beneficiario',
        'Id_Organizacion',
        'Monto',
        'Fecha',
    ];

    public function beneficiario()
    {
        return $this->belongsTo(Beneficiario::class, 'Id_Beneficiario', 'id_Beneficiario');
    }

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'Id_Organizacion', 'Id_Organizacion');
    }
}
