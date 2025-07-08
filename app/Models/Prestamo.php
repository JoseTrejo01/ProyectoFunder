<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $table = 'prestamos';
    protected $primaryKey = 'id'; // o el nombre real si es diferente

    public $timestamps = true; // Usa created_at y updated_at

    protected $fillable = [
        'socio_id',
        'nombre_caja_rural',
        'monto_solicitado',
        'plazo_meses',
        'destino',
        'tipo_credito',
        'fecha_solicitud',
        'porcentaje_mora_caja',
        'intereses_cobrados',
        'capital_social',
        'capital_trabajo',
        'reservas',
        'estado',
        'puntaje',
        'observaciones',
    ];

    // Relación con organización (socio)
    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'socio_id', 'Id_Organizacion');
    }
        public function pagos()
    {
        return $this->hasMany(Pago::class, 'prestamo_id');
    }

}
