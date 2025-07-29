<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emprendimiento extends Model
{
    protected $table = 'tbl_emprendimiento';
    protected $primaryKey = 'Id_Emprendimiento';
    public $timestamps = true;

    protected $fillable = [
        'Caja_Rural',
        'Id_Municipio',
        'Comunidad',
        'Socios_Hombres',
        'Socios_Mujeres',
        'Tipo_Negocio',
        'Ventas_Trimestrales',
        'Empleos_Hombres',
        'Empleos_Mujeres',
        'Fecha_Levantamiento',
        'Fecha_Inicio_Operaciones',
        'Id_Tecnico',
        'Id_Organizacion' // <-- agregamos este campo
    ];

    // Relación con Municipio
    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'Id_Municipio', 'Id_Municipio');
    }

    // Relación con Técnico (usuario)
    public function tecnico()
    {
        return $this->belongsTo(User::class, 'Id_Tecnico', 'Id_Usuario');
    }

    // Relación con Organización
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'Id_Organizacion', 'Id_Organizacion');
    }
}
