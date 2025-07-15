<?php

// app/Models/Invitado.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitado extends Model
{
    use HasFactory;

    protected $primaryKey = 'idinvitados';

    protected $fillable = [
        'actividad_id',
        'id_usuarios',
    ];

public function actividad()
{
    return $this->belongsTo(Actividad::class, 'actividad_id', 'actividad_id');
}

public function usuario()
{
    return $this->belongsTo(User::class, 'id_usuarios', 'id');
}
}
