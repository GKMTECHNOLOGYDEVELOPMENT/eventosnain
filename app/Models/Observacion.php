<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
    use HasFactory;

    protected $table = 'observaciones';

    protected $primaryKey = 'observacionid';

    public $timestamps = false; // Desactivar el manejo automático de timestamps

    protected $fillable = [
        'observacionreunion',
        'fechareunion',
        'observacioncontrato',
        'fechacontrato',
        'id_cliente',
        'observacionllamada',
        'fechallamada',

    ];

    protected $dates = [
        'fechareunion',
        'fechacontrato',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
}
