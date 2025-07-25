<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Socio;
use App\Models\Prestamo;
use App\Models\Aldea;
use App\Models\Beneficiario;

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

    // Relaciones

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'socio_id', 'Id_Organizacion');
    }

    public function aldea()
    {
        return $this->belongsTo(Aldea::class, 'Id_Aldea', 'Id_Aldea');
    }

    public function municipio()
    {
        return $this->aldea ? $this->aldea->municipio : null;
    }

    public function departamento()
    {
        return $this->municipio() ? $this->municipio()->departamento : null;
    }

    public function socios()
    {
        return $this->hasMany(Socio::class, 'Id_Organizacion', 'Id_Organizacion');
    }

    public function beneficiarios()
    {
        return $this->hasMany(Beneficiario::class, 'Id_Organizacion', 'Id_Organizacion');
    }
}