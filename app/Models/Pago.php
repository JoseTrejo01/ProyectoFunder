<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'prestamo_id',
        'fecha_pago',
        'monto_pagado',
        'observaciones',
    ];

    // Relación inversa con préstamo
    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class);
    }
}
