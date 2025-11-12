<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Support\Facades\Hash;              // <-- IMPORTANTE

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_ms_usuario';
    protected $primaryKey = 'Id_Usuario';
    public $timestamps = false;

    protected $fillable = [
        'Id_Rol', 'Usuario', 'Nombre_Usuario', 'Estado_Usuario', 'Contraseña',
        'Fecha_Ultima_Conexion', 'Primer_Ingreso', 'Fecha_Vencimiento',
        'Correo_Electronico', 'Creado_Por', 'Fecha_Creacion', 'Modificado_Por', 'Fecha_Modificacion',
        'email_verified_at'
    ];

    protected $hidden = ['Contraseña'];

    protected $casts = [
        'email_verified_at' => 'timestamp',
    ];

    // ====== MUTADOR PARA HASHEAR CONTRASEÑA (bcrypt) ======
    /**
     * Si recibimos texto plano, se hashea con bcrypt.
     * Si ya viene un hash bcrypt válido ($2y$...), se guarda tal cual.
     */
    public function setContraseñaAttribute($value)
    {
        if ($value === null || $value === '') {
            $this->attributes['Contraseña'] = $value;
            return;
        }

        // ¿ya parece un hash bcrypt ($2y$xx$ + 53 chars)?
        $isBcrypt = is_string($value) && preg_match('/^\$2y\$\d{2}\$.{53}$/', $value) === 1;

        $this->attributes['Contraseña'] = $isBcrypt ? $value : Hash::make($value);
    }
    // ======================================================

    public function getEmailAttribute() {
        return $this->Correo_Electronico;
    }

    public function getEmailForVerification() {
        return $this->Correo_Electronico;
    }

    public function getAuthPassword() {
        return $this->Contraseña;
    }

    public function getEmailForPasswordReset() {
        return $this->Correo_Electronico;
    }

    public function sendPasswordResetNotification($token) {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    public function passwordHistories() {
        return $this->hasMany(PasswordHistory::class, 'Id_Usuario', 'Id_Usuario');
    }

    public function rol() {
        return $this->belongsTo(Rol::class, 'Id_Rol', 'Id_Rol');
    }

    public function tienePermiso($nombreObjeto, $permiso) {
        $rol = $this->rol;
        if (!$rol) return false;

        $permisoColumna = 'Permiso_' . ucfirst(strtolower($permiso));
        return $rol->permisos()
            ->whereHas('objeto', function($q) use ($nombreObjeto) {
                $q->where('Objeto', $nombreObjeto);
            })
            ->where($permisoColumna, 1)
            ->exists();
    }
}