@php
    use App\Models\Page;
    use App\Models\Popup;
    // Nunca en el formulario de cotización (ahí la persona está pidiendo presupuesto) ni en la vista previa de un borrador.
    $popup = request()->routeIs('contact.show', 'cotizaciones.*') || Page::enVistaPrevia()
        ? null
        : Popup::paraMostrar(request()->routeIs('home'));
@endphp
@if($popup?->media)
    {{-- La ventana se abre por JavaScript (resources/js/popup.js) después del banner de cookies y respetando la frecuencia
         elegida en el panel. Sin JavaScript queda cerrada: nunca bloquea el contenido. --}}
    <dialog class="popup" data-popup="{{ $popup->id }}-{{ $popup->updated_at?->timestamp }}" data-frecuencia="{{ $popup->frecuencia }}" aria-label="{{ $popup->nombre }}">
        <button class="popup-cerrar" type="button" data-popup-cerrar aria-label="Cerrar el aviso"><span aria-hidden="true">✕</span></button>
        @if($popup->enlaceCompleto())
            <a class="popup-enlace" href="{{ $popup->enlaceCompleto() }}"{!! $popup->nueva_pestana ? ' target="_blank" rel="noopener"' : '' !!}>
                <x-foto :media="$popup->media" class="popup-imagen" sizes="(min-width: 700px) 640px, 92vw" :alt="$popup->texto_alternativo ?: ($popup->media->alt ?: $popup->nombre)" />
            </a>
        @else
            <x-foto :media="$popup->media" class="popup-imagen" sizes="(min-width: 700px) 640px, 92vw" :alt="$popup->texto_alternativo ?: ($popup->media->alt ?: $popup->nombre)" />
        @endif
    </dialog>
@endif
