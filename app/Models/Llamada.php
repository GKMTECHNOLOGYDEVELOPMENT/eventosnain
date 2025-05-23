<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Llamada extends Model
{
    protected $table = 'llamada'; // Nombre de la tabla si no es la convenci¨®n plural

    // Clave primaria personalizada
    protected $primaryKey = 'id_llamada';

    protected $fillable = [
        'cliente_id',
        'date',
        'observaciones',
        'user_id',
        'estado',
    ];
    public $timestamps = false;
}