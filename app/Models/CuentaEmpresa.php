<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaEmpresa extends Model
{
    use HasFactory;

    protected $table = 'cuentas_empresa';

    protected $fillable = [
        'empresa_id',
        'codigo',
        'nombre',
    ];

    /**
     * Relación con la empresa
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
