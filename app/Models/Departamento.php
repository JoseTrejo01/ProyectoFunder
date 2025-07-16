<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
<<<<<<< HEAD
    protected $table = 'tbl_departamento';
=======
    protected $table = 'tbl_departamento'; // usa el nombre real de tu tabla
>>>>>>> d70bdda475d362f8a33abf55ed6e0d1420a15beb
    protected $primaryKey = 'Id_Departamento';
    public $timestamps = false;

    protected $fillable = [
        'Nombre_Departamento',
    ];
<<<<<<< HEAD
=======

    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'Id_Departamento');
    }
>>>>>>> d70bdda475d362f8a33abf55ed6e0d1420a15beb
}
