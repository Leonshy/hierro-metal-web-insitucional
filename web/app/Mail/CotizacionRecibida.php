<?php

namespace App\Mail;

use App\Models\Cotizacion;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Aviso interno de cotización nueva. Los adjuntos NO viajan por correo (podrían ser maliciosos):
 * se listan y se descargan desde el panel, con sesión.
 */
class CotizacionRecibida extends Mailable
{
    public function __construct(public Cotizacion $cotizacion) {}

    public function envelope(): Envelope
    {
        $asunto = 'Nueva cotización de '.$this->cotizacion->nombre.($this->cotizacion->rubro ? ' · '.$this->cotizacion->rubro : '');

        return new Envelope(
            subject: $asunto,
            replyTo: $this->cotizacion->email ? [new Address($this->cotizacion->email, $this->cotizacion->nombre)] : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cotizacion',
            text: 'emails.cotizacion-texto',
            with: [
                'panelUrl' => url('/'.trim(config('sitio.admin_path'), '/').'/cotizaciones/'.$this->cotizacion->id.'/edit'),
            ],
        );
    }
}
