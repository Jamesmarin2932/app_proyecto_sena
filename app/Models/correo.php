<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class correo extends Model
{
    use HasFactory;
    protected $fillable=['correo','id_datos_clientes'];
}
