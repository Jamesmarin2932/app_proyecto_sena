<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Descripcion_factura extends Model
{
    use HasFactory;

    protected $fillable=['fecha_de_compra','producto','cantidad','sub_total','descuento','iva','total','id_factura'];
}
