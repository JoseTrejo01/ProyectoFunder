<?php
// app/Models/PasswordHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordHistory extends Model
{
    protected $table = 'tbl_ms_hist_contraseña';

    protected $fillable = [
        'Id_Usuario',
        'Contraseña',
        'Fecha_Creacion',
        'Creado_Por',
        'Fecha_Modificacion'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'Id_Usuario', 'Id_Usuario');
    }
}
