<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventUser extends Model
{
    // Definir la tabla asociada con el modelo
    protected $table = 'event_user';

    // Definir los campos que se pueden asignar en masa
    protected $fillable = [
        'event_id',
        'user_id',
      
    ];

    // Opcionalmente, si deseas permitir timestamps en tu modelo
    public $timestamps = true;

    // Definir la relación con el modelo Salida
    public function evento()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // Definir la relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
