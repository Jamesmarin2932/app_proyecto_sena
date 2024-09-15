<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dato_producto extends Model
{
    use HasFactory;

    protected $fillable = ['codigo', 'id_factura'];
}
