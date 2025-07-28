<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    // Si tu tabla no es "beneficiarios", sino "tbl_beneficiario":
    protected $table = 'tbl_beneficiario';
    protected $primaryKey = 'id_Beneficiario';
    public $timestamps = false; 
    // Si tu clave primaria no es "id", defínela (ejemplo: 'id_beneficiario')
    // protected $primaryKey = 'id_beneficiario';

    // Si no usas timestamps en la tabla, desactívalos:
    // public $timestamps = false;

    // Campos que puedes asignar masivamente
    protected $fillable = [
        'Nombre_Beneficiario',
        // otros campos que tenga tu tabla
    ];

    // Relaciones (si necesitas)
    // Ejemplo: Un beneficiario puede tener muchos préstamos
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'beneficiario_id');
    }
}
