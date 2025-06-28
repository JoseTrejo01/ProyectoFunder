<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPasswordNotification;

class User extends Authenticatable
{
    use Notifiable;

    // Nombre de la tabla en la base de datos
    protected $table = 'tbl_ms_usuario';

    // Clave primaria de la tabla
    protected $primaryKey = 'Id_Usuario';

    // Indica si el modelo usa timestamps (created_at, updated_at)
    public $timestamps = false;

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'Id_Rol',
        'Usuario',
        'Nombre_Usuario',
        'Estado_Usuario',
        'Contraseña',
        'Fecha_Ultima_Conexion',
        'Primer_Ingreso',
        'Fecha_Vencimiento',
        'Correo_Electronico',
        'Creado_Por',
        'Fecha_Creacion',
        'Modificado_Por',
        'Fecha_Modificacion'
    ];

    // Campos que deben ocultarse en las respuestas JSON
    protected $hidden = [
        'Contraseña',
    ];

    // ✅ AGREGAR: Mapeo del campo email
    public function getEmailAttribute()
    {
        return $this->Correo_Electronico;
    }

    // Método para obtener la contraseña (requerido por Laravel Auth)
    public function getAuthPassword()
    {
        return $this->Contraseña;
    }

    // ✅ YA TIENES: Método para obtener email para reset password
    public function getEmailForPasswordReset()
    {
        return $this->Correo_Electronico;
    }

    // ✅ YA TIENES: Método para enviar notificación personalizada
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

     public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class, 'Id_Usuario', 'Id_Usuario');
    }

    // Relación con la tabla tbl_ms_rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'Id_Rol', 'Id_Rol');
    }
    
    public function tienePermiso($nombreObjeto, $permiso)
    {
        $rol = $this->rol;
        if (!$rol) return false;

        $permisoColumna = 'Permiso_' . ucfirst(strtolower($permiso)); // Ej: Permiso_Consultar

        return $rol->permisos()
            ->whereHas('objeto', function($q) use ($nombreObjeto) {
                $q->where('Objeto', $nombreObjeto);
            })
            ->where($permisoColumna, 1)
            ->exists();
    }
}
