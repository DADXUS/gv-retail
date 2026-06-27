<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'barcode',
        'nombre',
        'precio',
        'stock',
    ];
}
