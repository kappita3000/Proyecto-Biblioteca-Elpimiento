<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CuentaCreadaNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // Usuario al que se enviará el correo

    /**
     * Crear una nueva instancia del Mailable.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Crear el mensaje del correo.
     */
    public function build()
    {
        return $this->subject('¡Cuenta creada con éxito!')
            ->view('emails.cuenta_creada') // Ruta de la vista
            ->with('user', $this->user);  // Pasar datos a la vista
    }
}
