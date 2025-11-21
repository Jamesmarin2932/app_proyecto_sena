<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asiento extends Model
{
    protected $fillable = [
        'empresa_id',
        'tipo',
        'fecha',
        'factura',
        'tercero_id',
        'cuenta',
        'concepto',
        'debito',
        'credito',
        'saldo',
        'consecutivo',
        'usuario_creador',
    ];

    // Relación con cliente/tercero
    public function tercero()
    {
        return $this->belongsTo(DatoCliente::class, 'tercero_id');
    }

    // Relación con Cuenta global
    public function cuentaInfo()
    {
        return $this->belongsTo(Cuenta::class, 'cuenta', 'codigo');
    }

    // Relación con Cuenta por empresa
    public function cuentaEmpresa()
    {
        return $this->belongsTo(CuentaEmpresa::class, 'cuenta', 'codigo')
            ->where('empresa_id', $this->empresa_id);
    }

    // Accesor para obtener siempre el nombre de cuenta correcto
    public function getNombreCuentaAttribute()
    {
        return $this->cuentaEmpresa->nombre ?? $this->cuentaInfo->nombre ?? $this->cuenta;
    }
}
