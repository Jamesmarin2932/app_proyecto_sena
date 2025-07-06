<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NombreProducto extends Model
{
    use HasFactory;

    protected $fillable=['Codigo','Nombre','Descripcion','Precio','Stock',];
}
