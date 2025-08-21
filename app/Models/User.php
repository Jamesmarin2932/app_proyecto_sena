<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    // 👇 Relación con empresas
    public function empresas(): BelongsToMany
    {
        return $this->belongsToMany(Empresa::class, 'empresa_usuario', 'user_id', 'empresa_id')
                    ->withTimestamps();
    }

    // 👇 Mutador para encriptar automáticamente el password
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? Hash::make($value) : null,
        );
    }
}
