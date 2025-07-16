<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class ActividadEconomica extends Model
{
    protected $table = 'tbl_actividad_economica';
    protected $primaryKey = 'Id_Actividad'; // Cambiado según tu tabla

    protected $fillable = [
        'Id_Beneficiario',
        'Tipo',
        'Numero',
        'Rubro',
        'Unidad_Medida',
        'Cantidad',
    ];

    public function socio()
    {
        return $this->belongsTo(Socio::class, 'Id_Beneficiario', 'Id_Beneficiario');
    }
}