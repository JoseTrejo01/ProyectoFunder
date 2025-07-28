<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criterio extends Model
{
    use HasFactory;
    protected $table = 'tbl_criterios';
    protected $primaryKey = 'Id_Criterio';
    public $incrementing = true;
   
    protected $fillable = ['subindice', 'variable', 'descripcion'];
    public function getRouteKeyName()
    {
        return 'Id_Criterio';
    }

    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class);
    }
}

