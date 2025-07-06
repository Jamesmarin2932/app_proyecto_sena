<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asiento extends Model
{
    use HasFactory;

    protected $table = 'asientos';

    protected $fillable = [
        'tercero_id',
        'cuenta',
        'fecha',
        'concepto',
        'debito',
        'credito',
        'saldo',
        'consecutivo',
        'tipo',       // Nuevo campo
        'factura',    // Nuevo campo
    ];

    // Relación con el modelo de terceros (datoClientes)
    public function tercero()
    {
        return $this->belongsTo(DatoCliente::class, 'tercero_id');
    }
}
