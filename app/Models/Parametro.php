<?php
// App\Models\Parametro.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    protected $table = 'tbl_parametros';
    public $timestamps = false;
    protected $primaryKey = 'Id_Parametro';

    protected $fillable = ['Parametro', 'Valor'];
}
