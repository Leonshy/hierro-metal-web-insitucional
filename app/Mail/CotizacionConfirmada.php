<?php

namespace App\Mail;

use App\Models\Cotizacion;
use App\Services\Cotizaciones\Destinatarios;
use App\Support\Contacto;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Str;

/**
 * «Pedido recibido» para quien completó el formulario. Sale desde la casilla oficial, así que NO repite lo que
 * escribió la persona (el formulario no puede servir para mandar texto ajeno a cualquier dirección): sólo un saludo
 * con el primer nombre, filtrado, y los datos de contacto del negocio.
 */
class CotizacionConfirmada extends Mailable
{
    public function __construct(public Cotizacion $cotizacion) {}

    public function envelope(): Envelope
    {
        $responder = Destinatarios::resolver()[0] ?? null;

        return new Envelope(
            subject: 'Recibimos tu pedido de cotización',
            replyTo: $responder ? [new Address($responder, config('app.name'))] : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cotizacion-confirmada',
            text: 'emails.cotizacion-confirmada-texto',
            with: [
                'primerNombre' => $this->primerNombre(),
                'telefono' => Contacto::telefono(),
                'whatsappUrl' => Contacto::whatsappUrl(),
            ],
        );
    }

    /** Sólo letras, apóstrofe y guion: un nombre con enlaces, puntos o símbolos no llega al saludo. */
    private function primerNombre(): string
    {
        $primero = Str::before(trim($this->cotizacion->nombre), ' ');
        $limpio = preg_replace("/[^\p{L}'\-]/u", '', $primero) ?? '';

        return Str::limit($limpio, 30, '');
    }
}
