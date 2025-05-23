<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_modulo',
        'marca',
        'modelo',
        'descripcion',
        'precio_compra',
        'precio_venta',
        'stock_total',
        'stock_minimo',
        'fecha_registro',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'fecha_registro' => 'date'
    ];
}
