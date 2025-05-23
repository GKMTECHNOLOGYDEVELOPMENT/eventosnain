<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuloImagen extends Model
{
    use HasFactory;
    protected $table = 'modulo_imagenes'; // Especifica el nombre correcto de la tabla

    protected $fillable = [
        'modulo_id',
        'nombre_archivo',
        'mime_type',
        'imagen_data',
        'es_principal'
    ];

    protected $casts = [
        'es_principal' => 'boolean',
    ];

    // ModuloImagen.php
    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }


    /**
     * Obtener la imagen como base64 para mostrarla en HTML
     */
    public function getImagenBase64Attribute()
    {
        return 'data:' . $this->mime_type . ';base64,' . base64_encode($this->imagen_data);
    }
}
