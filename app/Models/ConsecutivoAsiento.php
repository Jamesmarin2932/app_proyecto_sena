<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsecutivoAsiento extends Model
{
    use HasFactory;

    protected $table = 'consecutivos_asientos';
    
    protected $fillable = [
        'empresa_id',
        'tipo_asiento',
        'ultimo_consecutivo'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}