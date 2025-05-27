<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash; // Importar el facade Hash

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'nombre_usuario',
        'identificacion',
        'usuario',
        'password',
    ];

    protected $hidden = [
        'password', // Para no exponer la contraseña
        'remember_token',
    ];

    // Mutador para encriptar la contraseña
    public function setPasswordAttribute($value)
    {
        // Si se proporciona una contraseña, encripta la contraseña
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}
