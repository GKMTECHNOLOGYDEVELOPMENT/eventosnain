<?php
namespace App\Mail;

use App\Models\Actividad;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActualizacionEventoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $evento;
    public $usuario;

    public function __construct(Actividad $evento, $usuario)
    {
        $this->evento = $evento;
        $this->usuario = $usuario;
    }

    public function build()
    {
        return $this->subject('Actualización del evento: ' . $this->evento->titulo)
            ->view('email.actualizacion_evento');
    }
}
