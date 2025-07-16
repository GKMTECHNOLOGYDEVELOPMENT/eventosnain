<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    // Nombre de la tabla (opcional si sigue la convención)
    protected $table = 'servicios';

    // Clave primaria (opcional si es 'id')
    protected $primaryKey = 'id';

    // Tipos de datos que pueden ser asignados masivamente
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Opcional si quieres que use timestamps (created_at, updated_at)
    public $timestamps = true;
}
