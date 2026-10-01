// Mejoras del formulario de cotización. El formulario funciona igual sin JavaScript:
// esto sólo muestra los archivos elegidos, evita el doble envío y lleva la vista a los errores.
const formulario = document.getElementById('formulario-cotizacion');

if (formulario) {
    const entrada = formulario.querySelector('#archivos');
    const texto = formulario.querySelector('[data-archivos-texto]');
    const original = texto?.textContent ?? '';

    entrada?.addEventListener('change', () => {
        const nombres = [...entrada.files].map((archivo) => archivo.name);
        texto.textContent = nombres.length ? nombres.join(', ') : original;
    });

    formulario.addEventListener('submit', () => {
        const boton = formulario.querySelector('[data-enviando]');
        if (!boton || boton.disabled) return;
        // aria-disabled en vez de disabled: un botón deshabilitado no manda su valor ni se anuncia bien.
        boton.setAttribute('aria-disabled', 'true');
        boton.textContent = boton.dataset.enviando;
        boton.classList.add('cargando');
    });

    // Con errores, el foco va al aviso (y de ahí a los campos) sin que la persona tenga que buscarlos.
    document.getElementById('errores-formulario')?.focus({ preventScroll: false });
}
