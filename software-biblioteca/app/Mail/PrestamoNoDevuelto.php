<?php

namespace App\Mail;

use App\Models\Prestamo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PrestamoNoDevuelto extends Mailable
{
    use Queueable, SerializesModels;

    public $prestamo;

    public function __construct(Prestamo $prestamo)
    {
        $this->prestamo = $prestamo;
    }

    public function build()
    {
        return $this->view('emails.prestamo_no_devuelto')
                    ->subject('Devolución Pendiente - Aviso');
    }
}
