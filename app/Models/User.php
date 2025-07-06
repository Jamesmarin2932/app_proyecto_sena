<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles; // ← Agregado

class User extends Authenticatable
{
    use HasApiTokens, HasRoles; // ← Agregado HasRoles

    protected $fillable = [
        'nombre_usuario',
        'identificacion',
        'usuario',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Mutador para encriptar la contraseña
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}
