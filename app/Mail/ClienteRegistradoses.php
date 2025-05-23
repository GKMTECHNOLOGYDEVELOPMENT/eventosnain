<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class ClienteRegistradoses extends Mailable
{
    use Queueable, SerializesModels;

    public $cliente;
    public $userName;
    public $userEmail;
    public $userPasswordd;
    public $telefono;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($cliente, $userName, $userEmail, $userPasswordd, $telefono)
    {
        $this->cliente = $cliente;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->telefono = $telefono;

        // Configurar el correo de envío dinámicamente aquí
        Config::set('mail.mailers.smtp.username', $userEmail);
        Config::set('mail.mailers.smtp.password', $userPasswordd);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.cliente-registradoses')
            ->with([
                'nombre' => $this->cliente->nombre,
                'email' => $this->cliente->email,
                'userName' => $this->userName,
                'telefono' => $this->telefono,
                // Otros datos necesarios para el correo
            ]);
    }
}
