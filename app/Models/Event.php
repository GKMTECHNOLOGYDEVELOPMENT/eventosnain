<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'note',
        'type',
        'start',
        'end',
        'all_day',
        'user_id',
        'cliente_id',
        'estado',
    ];

    // Convertir estos campos en instancias de Carbon
    protected $dates = ['start', 'end'];

    // Relaci¨®n con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relaci¨®n con el modelo Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    // Desactivar los timestamps
    public $timestamps = false;
}
