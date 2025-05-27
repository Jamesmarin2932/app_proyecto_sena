<?php

// app/Models/Cuenta.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $fillable = ['codigo', 'nombre'];



    public function asientos()
    {
        return $this->hasMany(Asiento::class, 'cuenta', 'codigo');
    }
}
