<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'empresa',
        'telefono',
        'email',
        'tipo_cliente',
        'servicios',
        'mensaje',
        'status',
        'correo',
        'whatsapp',
        'reunion',
        'contrato',
        'fecharegistro',
        'user_id',
        'events_id',
        'llamada',
        'levantamiento',
        'documento'



    ];


    protected $table = 'cliente'; // Especifica la tabla si no sigue la convenci��n de nombres
    protected $primaryKey = 'id'; // Clave primaria

    public $timestamps = false; // Desactiva el manejo autom��tico de timestamps
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function salida()
    {
        return $this->belongsTo(Salida::class, 'events_id', 'id');
    }
    public function reuniones()
    {
        return $this->hasMany(Reunion::class, 'cliente_id');
    }

    public function evento()
    {
        return $this->belongsTo(Salida::class, 'events_id');
    }

    // public function eventos()
    // {
    //     return $this->hasMany(Event::class, 'cliente_id');
    // }
}
