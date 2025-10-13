<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asiento extends Model
{
    use HasFactory;

    protected $table = 'asientos';

    protected $fillable = [
        'empresa_id',   // 👈 aquí
        'tercero_id',
        'cuenta',
        'fecha',
        'concepto',
        'debito',
        'credito',
        'saldo',
        'consecutivo',
        'tipo',
        'factura',
        'usuario_creador',
    ];

    public function tercero()
    {
        return $this->belongsTo(DatoCliente::class, 'tercero_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function tipoConsecutivo()
{
    return $this->belongsTo(ConsecutivoAsiento::class, 'tipo', 'tipo_asiento')
        ->where('empresa_id', $this->empresa_id);
}
}
