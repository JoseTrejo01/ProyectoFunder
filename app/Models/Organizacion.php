<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizacion extends Model
{
    use HasFactory;

    protected $table = 'tbl_organizacion';
    protected $primaryKey = 'Id_Organizacion';
    public $timestamps = false;

    protected $fillable = [
        'Id_Aldea',
        'Id_Usuario',
        'Nombre_Organizacion',
        'Estado_Organizacion',
        'tiene_personeria_juridica',
        'fecha_personeria_juridica',
        'tiene_rtn',
        'rtn',
        'tiene_cuenta_bancaria',
    ];

    public function aldea()
    {
        return $this->belongsTo(Aldea::class, 'Id_Aldea', 'Id_Aldea');
    }

    public function socios()
    {
        return $this->hasMany(Socio::class, 'Id_Organizacion', 'Id_Organizacion');
    }

    public function beneficiarios()
    {
        return $this->hasMany(Beneficiario::class, 'Id_Organizacion', 'Id_Organizacion');
    }

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'socio_id', 'Id_Organizacion');
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'organizacion_id', 'Id_Organizacion');
    }

    public function evaluacion()
    {
        return $this->hasOne(Evaluacion::class, 'organizacion_id', 'Id_Organizacion');
    }
}
