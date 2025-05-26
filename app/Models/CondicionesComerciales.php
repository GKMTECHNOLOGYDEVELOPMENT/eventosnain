<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CondicionesComerciales extends Model
{
    use HasFactory;

    protected $table = 'condiciones_comerciales';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
