<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalidaUser extends Model
{
    // Definir la tabla asociada con el modelo
    protected $table = 'salida_user';

    // Definir los campos que se pueden asignar en masa
    protected $fillable = [
        'salida_id',
        'user_id',
        'meta_registros',
    ];

    // Opcionalmente, si deseas permitir timestamps en tu modelo
    public $timestamps = true;

    // Definir la relación con el modelo Salida
    public function salida()
    {
        return $this->belongsTo(Salida::class, 'salida_id');
    }

    // Definir la relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
