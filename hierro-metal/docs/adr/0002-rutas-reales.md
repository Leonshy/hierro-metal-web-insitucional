# ADR 0002 — Rutas reales en lugar del enrutador por hash del original

**Estado:** aceptado
**Fecha:** octubre 2026

## Contexto

El sitio del cliente es un único HTML que muestra u oculta ocho "páginas" según el hash
(`#/productos`). Para el usuario se ve igual a un sitio de varias páginas, pero:

- Google indexa una sola URL: las páginas de productos, servicios y FAQ no posicionan.
- No se pueden compartir enlaces directos a una sección (el vendedor no puede mandar "chapas").
- No hay título ni metadescripción por página.
- Las conversiones no se pueden atribuir a una página de origen.

## Decisión

Una ruta Laravel por página (CLAUDE.md §4), renderizada en servidor, con su `<title>`,
descripción, canonical y Open Graph. Las familias del catálogo pasan de anclas a páginas
propias (`/productos/{familia}`), y `/productos` conserva las anclas para quien quiera la vista
completa.

Si el cliente ya publicó el sitio con hash, las URLs con hash no llegan al servidor: se agrega un
script mínimo en `/` que redirige `#/productos` → `/productos` para los enlaces viejos.

## Consecuencias

- La navegación se siente igual al original.
- Cada página nueva suma superficie de SEO local.
