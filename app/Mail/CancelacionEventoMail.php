<?php
// app/Mail/CancelacionEventoMail.php

namespace App\Mail;

use App\Models\Actividad;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CancelacionEventoMail extends Mailable
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
        return $this->subject('Cancelación del evento: ' . $this->evento->titulo)
                    ->view('email.cancelacion_evento');
    }
}
