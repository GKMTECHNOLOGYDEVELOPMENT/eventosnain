<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $primaryKey = 'rol_id'; // Clave primaria

    protected $fillable = ['nombre']; // Ajusta esto según las columnas de tu tabla roles

    // Si estás usando una tabla diferente, asegúrate de definirla aquí
    protected $table = 'rol';
}
