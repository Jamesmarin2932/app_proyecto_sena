<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    'actividad_economica'
];


    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'empresa_usuario');
    }
}