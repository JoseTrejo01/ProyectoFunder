<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
<<<<<<< HEAD
    protected $table = 'tbl_municipio'; // nombre de tu tabla
    protected $primaryKey = 'Id_Municipio'; // clave primaria personalizada
    public $timestamps = false; // desactiva timestamps si no usas created_at / updated_at

    protected $fillable = [
        'Id_Departamento',
        'Nombre_Municipio',
    ];

    // Si quieres establecer relación con el modelo Emprendimiento:
    public function emprendimientos()
    {
        return $this->hasMany(Emprendimiento::class, 'Id_Municipio');
=======
    protected $table = 'tbl_municipio';
    protected $primaryKey = 'Id_Municipio';
    public $timestamps = false;

    protected $fillable = [
        'Nombre_Municipio',
        'Id_Departamento',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'Id_Departamento', 'Id_Departamento');
>>>>>>> 4afe5262c050935ee5c5c2afda518b7af2f8d855
    }
}
