@props(['mensaje' => 'Hola, quiero pedir una cotización.'])
@php use App\Support\Contacto; @endphp
<a class="whatsapp-flotante" href="{{ Contacto::whatsappUrl($mensaje) }}" target="_blank" rel="noopener"
   aria-label="Escribir por WhatsApp al {{ Contacto::telefono() }}">WhatsApp</a>
