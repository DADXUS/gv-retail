<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'tipo_comprobante',
        'serie_comprobante',
        'numero_comprobante',
        'fecha_emision',
        'cliente_id',
        'total',
    ];

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
