<?php

namespace App\Mail;

use App\Models\Prestamo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PrestamoAceptado extends Mailable
{
    use Queueable, SerializesModels;

    public $prestamo;

    /**
     * Create a new message instance.
     *
     * @param Prestamo $prestamo
     */
    public function __construct(Prestamo $prestamo)
    {
        $this->prestamo = $prestamo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Solicitud de Préstamo Aceptada')
                    ->view('emails.prestamo_aceptado');
    }
}
