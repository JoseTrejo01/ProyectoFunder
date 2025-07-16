<?php
// app/Models/Aldea.php
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
        return $this->belongsTo(Municipio::class, 'Id_Municipio');
    }
}
