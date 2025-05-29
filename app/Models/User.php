<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;




    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_password',
        'rol_id',
        'photo',
        'telefono'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'user_id'); // Ajusta 'user_id' al nombre de la columna de clave for��nea en la tabla 'clientes'
    }

    // Opcional: Relaci��n con el modelo Rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'rol_id');
    }

    public function eventos()
    {
        return $this->belongsToMany(Salida::class, 'salida_user');
    }

    public function salidas()
    {
        return $this->belongsToMany(Salida::class, 'salida_user', 'user_id', 'salida_id');
    }

    public function cotizaciones()
    {
        return $this->hasMany(\App\Models\Cotizacion::class, 'user_id');
    }
    public function events()
    {
        return $this->hasMany(\App\Models\Event::class, 'user_id');
    }

    public function eventUsers()
    {
        return $this->hasMany(\App\Models\EventUser::class, 'user_id');
    }
}
