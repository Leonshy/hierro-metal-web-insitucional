// Consentimiento de cookies (docs/08-seo.md §6). Nada de GTM/GA4/Meta Pixel
// se carga hasta que esta función decida hacerlo — el banner en
// resources/views/components/cookie-consent.blade.php solo llama a estas
// funciones, nunca hay un <script src="...gtm..."> incondicional en el <head>.
const STORAGE_KEY = 'sitio_consent';
const COOKIE_MAX_AGE_DAYS = 180;

function readConsent() {
    try {
        const raw = window.localStorage.getItem(STORAGE_KEY);

        return raw ? JSON.parse(raw) : null;
    } catch {
        return null;
    }
}

function writeConsent(consent) {
    window.localStorage.setItem(STORAGE_KEY, JSON.stringify(consent));

    // Se escribe también como cookie (no solo localStorage) para que el
    // servidor pueda leer la preferencia de marketing al procesar un envío
    // de formulario y decidir si manda o no el evento a Meta Conversions API
    // (App\Actions\Forms\StoreFormSubmission).
    const maxAge = COOKIE_MAX_AGE_DAYS * 24 * 60 * 60;
    document.cookie = `sitio_consent_marketing=${consent.marketing ? '1' : '0'}; path=/; max-age=${maxAge}; SameSite=Lax`;
    document.cookie = `sitio_consent_analytics=${consent.analytics ? '1' : '0'}; path=/; max-age=${maxAge}; SameSite=Lax`;
}

function loadGoogleTagManager() {
    const gtmId = document.documentElement.dataset.gtmId;

    if (!gtmId || window.__sitioGtmLoaded) {
        return;
    }
    window.__sitioGtmLoaded = true;

    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });

    const script = document.createElement('script');
    script.async = true;
    script.src = `https://www.googletagmanager.com/gtm.js?id=${gtmId}`;
    document.head.appendChild(script);
}

function loadMetaPixel() {
    const pixelId = document.documentElement.dataset.metaPixelId;

    if (!pixelId || window.__sitioPixelLoaded) {
        return;
    }
    window.__sitioPixelLoaded = true;

    /* eslint-disable */
    !(function (f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function () {
            n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = true;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = true;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s);
    })(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
    /* eslint-enable */

    window.fbq('init', pixelId);
    window.fbq('track', 'PageView');

    // Si un formulario se envió en esta misma navegación (redirect con
    // `meta_event_id` en la sesión), disparar el evento del Pixel con el
    // MISMO event_id que ya mandó el servidor — deduplicación real, no dos
    // conversiones por un solo envío (docs/08-seo.md §6).
    if (window.sitioQueuedLeadEventId) {
        window.fbq('track', 'Lead', {}, { eventID: window.sitioQueuedLeadEventId });
    }
}

function applyConsent(consent) {
    if (consent.analytics) {
        loadGoogleTagManager();
    }
    if (consent.marketing) {
        loadMetaPixel();
    }
}

document.addEventListener('alpine:init', () => {
    window.Alpine.data('sitioCookieConsent', () => ({
        visible: false,
        configuring: false,
        analytics: false,
        marketing: false,
        // Verdadero sólo cuando la persona vuelve a abrir preferencias que ya había elegido: ahí se puede cerrar sin cambiar nada.
        reopened: false,

        init() {
            const saved = readConsent();

            if (saved) {
                applyConsent(saved);
                this.visible = false;
            } else {
                this.visible = true;
            }

            window.addEventListener('sitio:reopen-consent', () => {
                const saved = readConsent();
                const current = saved ?? { analytics: false, marketing: false };
                this.analytics = current.analytics;
                this.marketing = current.marketing;
                this.configuring = true;
                // «Cerrar» depende de que ya exista una elección guardada, no de cómo se abrió el panel:
                // mientras la persona no haya aceptado ni rechazado nada, tiene que decidir.
                this.reopened = saved !== null;
                this.visible = true;
            });

            // El botón de WhatsApp se acomoda según la altura real del panel (cambia entre el aviso y las preferencias).
            this.$watch('visible', () => this.syncHeight());
            this.$watch('configuring', () => this.syncHeight());
            this.syncHeight();
        },

        syncHeight() {
            this.$nextTick(() => {
                const height = this.visible && this.$refs.panel ? this.$refs.panel.offsetHeight : 0;
                document.documentElement.style.setProperty('--cookies-alto', `${height}px`);
            });
        },

        // Cierra sin tocar lo que ya estaba guardado.
        close() {
            this.visible = false;
            this.configuring = false;
            this.reopened = false;
        },

        acceptAll() {
            const consent = { analytics: true, marketing: true };
            writeConsent(consent);
            applyConsent(consent);
            this.visible = false;
            this.reopened = false;
        },

        rejectAll() {
            const consent = { analytics: false, marketing: false };
            writeConsent(consent);
            this.visible = false;
            this.reopened = false;
        },

        saveConfigured() {
            const consent = { analytics: this.analytics, marketing: this.marketing };
            writeConsent(consent);
            applyConsent(consent);
            this.visible = false;
            this.reopened = false;
        },
    }));
});
