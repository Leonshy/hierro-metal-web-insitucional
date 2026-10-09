// Pop-up de imagen con enlace (se carga desde el panel: Contenido → Pop-ups).
// Usa <dialog>: el navegador se ocupa del foco, de Escape y de que el resto de la página quede inerte.
// Guarda sólo que ya se mostró (en la pestaña o por un día, según el panel): es un dato necesario para
// no repetir el aviso, no mide ni identifica a nadie.
const CLAVE = 'hm-popup:';
const UN_DIA = 24 * 60 * 60 * 1000;

function almacen(tipo) {
    try {
        return window[tipo];
    } catch {
        return null;
    }
}

function yaSeVio(id, frecuencia) {
    if (frecuencia === 'siempre') return false;

    try {
        if (frecuencia === 'dia') {
            const visto = Number(almacen('localStorage')?.getItem(CLAVE + id));
            return visto > 0 && Date.now() - visto < UN_DIA;
        }

        return almacen('sessionStorage')?.getItem(CLAVE + id) === '1';
    } catch {
        return false;
    }
}

function marcarVisto(id, frecuencia) {
    try {
        if (frecuencia === 'dia') almacen('localStorage')?.setItem(CLAVE + id, String(Date.now()));
        else if (frecuencia !== 'siempre') almacen('sessionStorage')?.setItem(CLAVE + id, '1');
    } catch {
        // Sin almacenamiento disponible: el aviso se muestra igual, sólo podría repetirse.
    }
}

// El banner de cookies va primero: el pop-up espera a que la persona elija.
function cuandoNoHayBanner(accion) {
    if (!document.body.classList.contains('cookies-visible')) return accion();

    const observador = new MutationObserver(() => {
        if (!document.body.classList.contains('cookies-visible')) {
            observador.disconnect();
            setTimeout(accion, 600);
        }
    });
    observador.observe(document.body, { attributes: true, attributeFilter: ['class'] });
}

function abrir(dialogo) {
    const imagen = dialogo.querySelector('img');
    const mostrar = () => {
        if (dialogo.open || typeof dialogo.showModal !== 'function') return;
        dialogo.showModal();
        marcarVisto(dialogo.dataset.popup, dialogo.dataset.frecuencia);
    };

    // La imagen se pide recién ahora (está en una ventana cerrada): se espera a tenerla para no mostrar un marco vacío.
    if (imagen && !imagen.complete) {
        imagen.loading = 'eager';
        const listo = () => mostrar();
        imagen.addEventListener('load', listo, { once: true });
        imagen.addEventListener('error', () => dialogo.remove(), { once: true });
        setTimeout(listo, 4000);
    } else {
        mostrar();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const dialogo = document.querySelector('dialog.popup');
    if (!dialogo || yaSeVio(dialogo.dataset.popup, dialogo.dataset.frecuencia)) return;

    dialogo.querySelector('[data-popup-cerrar]')?.addEventListener('click', () => dialogo.close());
    // Un clic en el fondo oscuro (fuera de la imagen) también lo cierra.
    dialogo.addEventListener('click', (evento) => {
        if (evento.target === dialogo) dialogo.close();
    });

    setTimeout(() => cuandoNoHayBanner(() => abrir(dialogo)), 1200);
});
