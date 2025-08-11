<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatoCliente extends Model
{
    use HasFactory;

    protected $table = 'dato_clientes';

    protected $fillable = [
        'tipo_identificacion',
        'numero_identificacion',
        'nombres',
        'apellidos',
        'razon_social',
        'tipo_persona',
        'tipo_tercero',
        'direccion',
        'departamento',
        'ciudad',
        'codigo_postal',
        'pais',
        'telefono',
        'correo',
        'actividad_economica',
        'observaciones',
        'cuenta_gasto',
        'empresa_id',
    ];

    // Relación con facturas (un cliente tiene muchas facturas)
    public function facturas()
    {
        return $this->hasMany(Factura::class, 'id_cliente');
    }

    // Relación con asientos (un cliente puede estar en muchos asientos como tercero)
    public function asientos()
    {
        return $this->hasMany(Asiento::class, 'tercero_id');
    }
}
