<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nombre_producto extends Model
{
    use HasFactory;

    protected $fillable=['Nombre','Descripcion','Precio','Stock','id_datos_productos'];
}
