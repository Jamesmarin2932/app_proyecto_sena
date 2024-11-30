<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descripcion_factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_identificacion',
        'numero_identificacion',
        'cliente',
        'fecha',
        'codigo_del_producto',
        'producto',
        'cantidad',
        'precio_unitario',
        'sub_total',
        'descuento',
        'iva',
        'total',
        'numero_factura'
    ];
}
