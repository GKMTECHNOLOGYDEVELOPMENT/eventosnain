<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etiqueta extends Model
{
    use HasFactory;
    // app/Models/Etiqueta.php
protected $fillable = ['nombre', 'color', 'icono', 'user_id'];

public function user()
{
    return $this->belongsTo(User::class);
}
}
