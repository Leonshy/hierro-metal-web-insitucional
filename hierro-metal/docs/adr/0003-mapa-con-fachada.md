# ADR 0003 — Google Maps detrás de una fachada de consentimiento

**Estado:** aceptado
**Fecha:** octubre 2026

## Contexto

La política de privacidad del propio cliente avisa que Google puede registrar datos al cargar el
mapa y sugiere no abrir la página si no se quiere. Cargar el iframe apenas se entra contradice
ese texto, además de sumar ~500 KB y varias conexiones a la página de Ubicación.

## Decisión

En lugar del iframe se muestra una **fachada** con el estilo del original (el fondo rayado
diagonal del sustituto de la vista previa, el pin en amarillo y la dirección), con dos acciones:
**"Cargar mapa"** (inserta el iframe en ese momento) y **"Abrir en Google Maps"** (enlace
directo). La elección de cargar se recuerda en `localStorage` para esa sesión.

## Consecuencias

- La página de Ubicación carga instantáneo y cumple su propia política de privacidad.
- Un clic más para quien quiere el mapa embebido; "Cómo llegar" sigue a un toque.
