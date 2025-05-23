<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CotizacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $message;
    public $attachmentPath;

    public function __construct($subject, $message, $attachmentPath = null)
    {
        $this->subject = $subject;
        $this->message = $message; // Debe ser una cadena de texto
        $this->attachmentPath = $attachmentPath;
    }

    public function build()
    {
        $mail = $this->view('email.cotizacion')
            ->subject($this->subject)
            ->with('message', $this->message); // Asegúrate de que $this->message es una cadena de texto

        if ($this->attachmentPath) {
            $mail->attach(storage_path('app/public/' . $this->attachmentPath));
        }

        return $mail;
    }
}