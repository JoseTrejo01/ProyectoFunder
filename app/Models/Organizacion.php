<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function beneficiarios() {
    return $this->hasMany(Beneficiario::class, 'Id_Organizacion');
}
}
