<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_razon_social',
        'nombre_comercial',
        'nit',
        'direccion',
        'ciudad',
        'departamento',
        'pais',
        'actividad_economica',
    ];

    // 👇 Relación con usuarios
    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'empresa_usuario', 'empresa_id', 'user_id')
                    ->withTimestamps();
    }
}
