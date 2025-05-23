<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;

    // Define la tabla asociada con el modelo
    protected $table = 'cotizaciones';

    // Define la clave primaria
    protected $primaryKey = 'id_cotizaciones';

    // Define los campos que son asignables en masa
    protected $fillable = [
        'recipient_email',
        'subject',
        'message',
        'user_id',
        'attachment',
        'cliente_id',
        'money', // Agrega el campo 'money' aquí
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el modelo Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    // Desactivar los timestamps
    public $timestamps = false;
}
