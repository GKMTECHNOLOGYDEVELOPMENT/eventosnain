<?php
namespace App\Mail;

use App\Models\Actividad;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitacionEventoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $evento;
    public $usuario;

    public function __construct(Actividad $evento, $usuario)
    {
        $this->evento = $evento;
        $this->usuario = $usuario; // El usuario que recibe la invitación
    }

    public function build()
    {
        return $this->subject('Nueva invitación al evento: ' . $this->evento->titulo)
            ->view('email.invitacion_evento');
    }
}

