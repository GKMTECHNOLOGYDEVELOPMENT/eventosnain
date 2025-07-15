<?php

// app/Models/Actividad.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;

    protected $primaryKey = 'actividad_id';
    protected $table = 'actividades'; // Especifica la tabla si no sigue la convenci��n de nombres

    protected $fillable = [
        'titulo',
        'etiqueta',
        'fechainicio',
        'fechafin',
        'todoeldia',
        'enlaceevento',
        'ubicacion',
        'descripcion',
        'user_id',
    ];

    // Relación con User (creador)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

      // Relación con User (creador)
    public function Usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    // Relación con invitados
 public function invitados()
{
    return $this->hasMany(Invitado::class, 'actividad_id', 'actividad_id');
}
}
