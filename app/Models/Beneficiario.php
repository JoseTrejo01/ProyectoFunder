<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // ✅ Import correcto del trait
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Beneficiario extends Model
{
    use HasFactory;

    protected $table = 'tbl_beneficiario';
    protected $primaryKey = 'id_Beneficiario';
    public $timestamps = false;

    protected $fillable = [
        'Nombre_Beneficiario',
        'Tipo_De_Socio',
        'edad',
        'Id_Organizacion',
        // agrega otros campos que necesites
    ];

    // Relación con Ahorros
    public function ahorros()
    {
        return $this->hasMany(Ahorro::class, 'Id_Beneficiario', 'id_Beneficiario');
    }

    // Relación con Organización
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'Id_Organizacion', 'Id_Organizacion');
    }
}
