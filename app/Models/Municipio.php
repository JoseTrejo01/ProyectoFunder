<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'tbl_municipio';
    protected $primaryKey = 'Id_Municipio';
    public $timestamps = false;

    protected $fillable = [
        'Id_Departamento',
        'Nombre_Municipio',
    ];

    // Relación con Departamento (muchos municipios pertenecen a un departamento)
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'Id_Departamento');
    }

    // Relación con Emprendimiento (un municipio tiene muchos emprendimientos)
    public function emprendimientos()
    {
        return $this->hasMany(Emprendimiento::class, 'Id_Municipio');
    }

    // Relación con Aldeas (un municipio tiene muchas aldeas)
    public function aldeas()
    {
        return $this->hasMany(Aldea::class, 'Id_Municipio');
    // Relación con Organizacion (un municipio tiene muchas organizaciones)
    }
    public function coordenada()
{
    return $this->hasOne(CoordenadaMunicipio::class, 'Id_Municipio');
}

}
