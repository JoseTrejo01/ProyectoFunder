<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Socio;
use App\Models\Aldea;
use App\Models\Beneficiario;
use App\Models\Prestamo;
use App\Models\Evaluacion;

class Organizacion extends Model
{
    protected $table = 'tbl_organizacion';
    protected $primaryKey = 'Id_Organizacion';
    public $timestamps = false;

    protected $fillable = [
        'Id_Aldea',
        'Id_Usuario',
        'Nombre_Organizacion',
        'Estado_Organizacion',
    ];

    // Relación con Aldea
    public function aldea()
    {
        return $this->belongsTo(Aldea::class, 'Id_Aldea', 'Id_Aldea');
    }

    // Relación indirecta con Municipio
    public function municipio()
    {
        return $this->aldea ? $this->aldea->municipio : null;
    }

    // Relación indirecta con Departamento
    public function departamento()
    {
        return $this->municipio() ? $this->municipio()->departamento : null;
    }

    // Relación con Socios
    public function socios()
    {
        return $this->hasMany(Socio::class, 'Id_Organizacion', 'Id_Organizacion');
    }

    // Relación con Beneficiarios
    public function beneficiarios()
    {
        return $this->hasMany(Beneficiario::class, 'Id_Organizacion', 'Id_Organizacion');
    }

    // Relación con Préstamos
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'socio_id', 'Id_Organizacion');
    }

    // Relación con Evaluaciones
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'id_organizacion', 'Id_Organizacion');
    }
}
