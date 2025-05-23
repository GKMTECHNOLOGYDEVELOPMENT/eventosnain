<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atencion extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'atencion';

    // La clave primaria de la tabla
    protected $primaryKey = 'id_atencion';

    // Indica que la clave primaria es un entero
    protected $keyType = 'int';

    // Indica si el campo de la clave primaria es auto-incrementable
    public $incrementing = true;

    // Si la tabla no tiene campos de marca de tiempo
    public $timestamps = false;

    // Los atributos que son asignables en masa
    protected $fillable = [
        'fecha',
        'conclusion',
        'user_id',
        'cliente_id',
    ];

    // Los atributos que deberían ser ocultos en arrays
    protected $hidden = [];

    // Los atributos que deben ser accesibles a través de un array
    protected $casts = [
        'fecha' => 'date',
    ];
}