<?php

namespace App\Mail;

use App\Models\Prestamo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PrestamoDevuelto extends Mailable
{
    use Queueable, SerializesModels;

    public $prestamo;

    public function __construct(Prestamo $prestamo)
    {
        $this->prestamo = $prestamo;
    }

    public function build()
    {
        return $this->view('emails.prestamo_devuelto')
                    ->subject('Libro Devuelto - Confirmación');
    }
}
