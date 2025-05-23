<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CotizacionProducto extends Model
{
    use HasFactory;

    protected $table = 'cotizacion_productos';
    protected $primaryKey = 'id';

    public $timestamps = false;


    protected $fillable = [
        'cotizacion_id',
        'modulo_id',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];

    // Relación con la cotización
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class, 'cotizacion_id');
    }

    // Relación con el módulo/producto
    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }

    // Método para calcular el subtotal automáticamente
    public function calcularSubtotal()
    {
        $this->subtotal = $this->cantidad * $this->precio_unitario;
        return $this;
    }
}
