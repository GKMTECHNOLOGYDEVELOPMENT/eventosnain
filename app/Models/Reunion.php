<?php
// Reunion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Reunion extends Model
{
    use HasFactory;

    protected $table = 'reunion';

    // Clave primaria personalizada
    protected $primaryKey = 'id_reunion';

    protected $fillable = [
        'fecha_hora',
        'observacion',
        'cliente_id',
        'tema',
        'userid',
        'zoom',
        'estado',
    ];

    protected $dates = ['fecha_hora'];

    public $timestamps = false;

    public function setEstadoAttribute($value)
    {
        $validValues = ['PENDIENTE', 'REALIZADO'];
        if (in_array($value, $validValues)) {
            $this->attributes['estado'] = $value;
        } else {
            // Manejo de errores o valor predeterminado si el valor no es válido
            $this->attributes['estado'] = 'PENDIENTE'; // Valor predeterminado
        }
    }

    // Definir la relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id'); // 'userid' es la clave foránea en 'reunion' y 'id' es la clave primaria en 'users'
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
