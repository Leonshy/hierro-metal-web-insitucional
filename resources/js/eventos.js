// Eventos de conversión (docs/08-seo.md §6). El del formulario se dispara en la página de gracias (consent.js,
// deduplicado con la API de Meta). Acá: el clic en cualquier botón o enlace de WhatsApp.
//
// No consulta el consentimiento por su cuenta: usa las marcas que sólo deja consent.js *después* de que la
// persona aceptó y cargó la herramienta (`__sitioGtmLoaded`, `__sitioPixelLoaded`). Sin permiso, no se envía nada.
document.addEventListener('click', (evento) => {
    const enlace = evento.target.closest?.('a[href^="https://wa.me/"]');

    if (!enlace) return;

    if (window.__sitioGtmLoaded && Array.isArray(window.dataLayer)) {
        window.dataLayer.push({ event: 'whatsapp_click', pagina: window.location.pathname });
    }

    if (window.__sitioPixelLoaded && typeof window.fbq === 'function') {
        window.fbq('track', 'Contact');
    }
});
