<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DatoCliente;
use App\Models\DetalleFactura;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_factura', 'cliente_id', 'fecha', 'sub_total', 'iva', 'total', 'descuento'
    ];

    public function cliente()
{
    return $this->belongsTo(DatoCliente::class, 'cliente_id');
}

public function detalles()
{
    return $this->hasMany(DetalleFactura::class);
}

}
