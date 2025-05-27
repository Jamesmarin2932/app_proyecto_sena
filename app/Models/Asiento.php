<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asiento extends Model
{
    protected $fillable = [
        'tipo',
        'factura',
        'tercero',
        'cuenta',
        'fecha',
        'concepto',
        'debito',
        'credito',
        'saldo',
        'consecutivo',
    ];
}
