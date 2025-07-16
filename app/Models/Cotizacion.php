<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'cotizaciones';
    protected $primaryKey = 'id';

    protected $fillable = [
        'codigo_cotizacion',
        'fecha_emision',
        'cliente_id',
        'validez',
        'id_servicio',
        'condiciones_comerciales',
        'observaciones',
        'subtotal_sin_igv',
        'igv',
        'total_con_igv',
        'estado',
        'user_id'
    ];

    protected $dates = [
        'fecha_emision',
        'created_at',
        'updated_at'
    ];

    // Relación con el cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    // Relación con los productos/modulos de la cotización
    public function productos()
    {
        return $this->hasMany(CotizacionProducto::class, 'cotizacion_id');
    }

    // Método para calcular la fecha de vencimiento
    public function getFechaVencimientoAttribute()
    {
        return $this->fecha_emision->addDays($this->validez);
    }

    // Método para verificar si está vencida
    public function getEstaVencidaAttribute()
    {
        return now()->greaterThan($this->getFechaVencimientoAttribute());
    }

    public function detalleProductos()
    {
        return $this->hasMany(CotizacionProducto::class, 'cotizacion_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function encargado()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

        public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}
