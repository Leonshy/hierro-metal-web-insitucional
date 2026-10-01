{{-- Banner de consentimiento (D5). Bloquea GA4 y Meta hasta que la persona acepta (resources/js/consent.js).
     «Rechazar todo» pesa lo mismo que «Aceptar todo». Texto: docs/07 §3.10. --}}
<div x-data="sitioCookieConsent()" x-effect="document.body.classList.toggle('cookies-visible', visible)" @keydown.escape.window="if (reopened) close()">
    <div class="cookies" x-ref="panel" x-show="visible" x-cloak role="dialog" aria-modal="false" aria-label="Preferencias de cookies">
        <div class="contenedor">
            <template x-if="! configuring">
                <div class="cookies-cuerpo">
                    <p>Usamos cookies para medir cuánta gente visita el sitio y para saber si nuestros anuncios funcionan. Podés aceptar todas, rechazar todas o elegir cuáles. <a href="{{ url('/privacidad') }}">Más información en la política de privacidad</a></p>
                    <div class="botonera">
                        <button class="btn btn-linea-amarilla" type="button" @click="rejectAll()">Rechazar todo</button>
                        <button class="btn btn-linea-amarilla" type="button" @click="configuring = true">Configurar</button>
                        <button class="btn btn-amarillo" type="button" @click="acceptAll()">Aceptar todo</button>
                    </div>
                </div>
            </template>
            <template x-if="configuring">
                <div class="cookies-cuerpo">
                    <p>Elegí qué cookies permitís. Las necesarias no se pueden desactivar.</p>
                    <div class="cookies-opciones">
                        <label class="opcion-fija"><input type="checkbox" checked disabled><span>Necesarias <small>(siempre activas)</small></span></label>
                        <label><input type="checkbox" x-model="analytics"><span>Medición de visitas <small>(Google Analytics)</small></span></label>
                        <label><input type="checkbox" x-model="marketing"><span>Publicidad <small>(Meta: Facebook e Instagram)</small></span></label>
                    </div>
                    <div class="botonera">
                        <button class="btn btn-linea-amarilla" type="button" @click="rejectAll()">Rechazar todo</button>
                        <button class="btn btn-amarillo" type="button" @click="saveConfigured()">Guardar preferencias</button>
                        {{-- Sólo al reabrir las preferencias ya elegidas: la primera vez hay que decidir, así que no se puede cerrar sin elegir. --}}
                        <button class="btn btn-cerrar" type="button" x-show="reopened" x-cloak @click="close()">Cerrar <span aria-hidden="true">✕</span></button>
                    </div>
                </div>
            </template>
        </div>
    </div>
    <button class="cookies-abrir" type="button" x-show="! visible" x-cloak
            @click="window.dispatchEvent(new CustomEvent('sitio:reopen-consent'))" aria-label="Cambiar preferencias de cookies">Cookies</button>
</div>
