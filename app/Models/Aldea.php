<?php
<<<<<<< HEAD
// app/Models/Aldea.php
=======

>>>>>>> 4afe5262c050935ee5c5c2afda518b7af2f8d855
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aldea extends Model
{
    protected $table = 'tbl_aldea';
    protected $primaryKey = 'Id_Aldea';
    public $timestamps = false;

    protected $fillable = [
        'Nombre_Aldea',
        'Id_Municipio',
    ];

    public function municipio()
    {
<<<<<<< HEAD
        return $this->belongsTo(Municipio::class, 'Id_Municipio');
=======
        return $this->belongsTo(Municipio::class, 'Id_Municipio', 'Id_Municipio');
>>>>>>> 4afe5262c050935ee5c5c2afda518b7af2f8d855
    }
}
