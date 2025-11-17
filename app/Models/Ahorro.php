<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ahorro extends Model
{
    use HasFactory;

    protected $table = 'tbl_ahorros';
<<<<<<< HEAD
    protected $primaryKey = 'id_Ahorro'; // 
    public $timestamps = false;
=======
    protected $primaryKey = 'id'; // ✔️ ESTA ES LA CLAVE REAL
    public $timestamps = true;    // ✔️ Tu tabla sí tiene created_at / updated_at
>>>>>>> origin/cambios-seguridad

    protected $fillable = [
        'Id_Organizacion',   
        'Id_Beneficiario',
        'Id_Organizacion',
        'Monto',
        'Fecha',
    ];

<<<<<<< HEAD
    /**
     * Relación con Beneficiario:
     * Un ahorro pertenece a un beneficiario.
     */
=======
>>>>>>> origin/cambios-seguridad
    public function beneficiario()
    {
        return $this->belongsTo(Beneficiario::class, 'Id_Beneficiario', 'Id_Beneficiario');
    }

<<<<<<< HEAD
    /**
     * Relación con Organizacion:
     * Un ahorro pertenece a una organización.
     */
=======
>>>>>>> origin/cambios-seguridad
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'Id_Organizacion', 'Id_Organizacion');
    }
}
