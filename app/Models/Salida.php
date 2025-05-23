<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    use HasFactory;

    // Especifica la tabla si el nombre es diferente del modelo en plural
    protected $table = 'salida';

    // Especifica si la tabla usa timestamps
    public $timestamps = false;

    // Define los campos que se pueden llenar mediante asignaci¨®n masiva
    protected $fillable = [
        'title',
        'note',
        'start',
        'end',
        'all_day',
        'meta_registros',
        'user_id',
    ];

    // Define los tipos de datos de los campos de la base de datos
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'all_day' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'salida_user', 'salida_id', 'user_id');
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'events_id', 'id');
    }
}
