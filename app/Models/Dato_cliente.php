<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dato_cliente extends Model
{
    use HasFactory;

    protected $table = 'dato_clientes';

    protected $fillable=[
        
        'tipo_identificacion',
        'numero_identificacion',
        'nombres',
        'apellidos',
        'direccion',
        'ciudad',
        'telefono',
        'correo'
    ];
}
