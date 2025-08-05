<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoordenadaMunicipio extends Model
{
    protected $table = 'tbl_coordenadas_municipio';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Id_Departamento',
        'Id_Municipio',
        'coordenada_x',
        'coordenada_y',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'Id_Departamento');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'Id_Municipio');
    }
}
