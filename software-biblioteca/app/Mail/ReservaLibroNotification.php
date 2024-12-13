<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservaLibroNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $libro;
    public $prestamo;
    public $nombreSolicitante;

    /**
     * Create a new message instance.
     */
    public function __construct($libro, $prestamo, $nombreSolicitante)
    {
        $this->libro = $libro;
        $this->prestamo = $prestamo;
        $this->nombreSolicitante = $nombreSolicitante;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Confirmación de Reserva de Libro')
                    ->view('emails.reserva_libro')
                    ->with([
                        'tituloLibro' => $this->libro->titulo,
                        'fechaRecojo' => $this->prestamo->fecha_solicitud,
                        'nombreSolicitante' => $this->nombreSolicitante,
                    ]);
    }
}
