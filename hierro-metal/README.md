# Web Institucional Hierro Metal S.R.L.

Proyecto de cliente de **webparaguay**. Sitio institucional y de captación de cotizaciones para
Hierro Metal S.R.L. — importación y venta de materiales metálicos y metalúrgicos, Fernando de la
Mora, Central.

Se construye con **el mismo proceso que Dante** (fases 0–10, tres hitos de aprobación del
cliente) y **reutilizando el backend y el panel de Dante** como base, adaptado a este dominio.

El cliente ya armó una versión propia del sitio (`referencia/sitio-cliente-original.html`). Es la
referencia de **contenido y aspecto gráfico**: el sitio nuevo le es fiel, y lo mejora.

## Por dónde empezar

| Documento | Qué contiene |
|---|---|
| [`CLAUDE.md`](CLAUDE.md) | **Leer primero.** Contexto, stack, reglas no negociables |
| [`PLAN.md`](PLAN.md) | Las 11 fases, con tareas, hitos y definición de "hecho" |
| [`docs/00-legajo-tecnico.md`](docs/00-legajo-tecnico.md) | Legajo estándar: alcance, negocio, esfuerzo, riesgos, KPIs |
| [`docs/01-inventario-contenido.md`](docs/01-inventario-contenido.md) | Todo el contenido del sitio del cliente, página por página. Fuente de verdad para los seeders |
| [`docs/02-sistema-diseno.md`](docs/02-sistema-diseno.md) | Tokens, tipografía y componentes extraídos del sitio del cliente |
| [`docs/03-backend-desde-dante.md`](docs/03-backend-desde-dante.md) | Qué se reutiliza de Dante, qué se apaga, qué módulos son nuevos, modelo de datos |
| [`docs/05-medidas-catalogo.md`](docs/05-medidas-catalogo.md) | Medidas del catálogo 2026 transcritas, contactos y decisiones sobre sus 13 dudas |
| [`docs/06-correcciones-catalogo-para-cliente.md`](docs/06-correcciones-catalogo-para-cliente.md) | Documento para el cliente: 12 correcciones posibles al catálogo |
| [`docs/07-copy-final.md`](docs/07-copy-final.md) | Copy final: 5 cambios puntuales al texto del cliente y los textos nuevos |
| [`PRODUCT.md`](PRODUCT.md) · [`DESIGN.md`](DESIGN.md) | Contexto de producto y sistema visual documentado (North Star «La etiqueta de stock») |
| [`diseno/`](diseno/) | `tokens.css`, `componentes.css`, guía de componentes y 3 pantallas en alta fidelidad (Home, ficha de familia, Contacto) |
| [`docs/ux-flows/`](docs/ux-flows/UX-FLOWS.md) | Hito 1: casos de uso, diagramas, wireframes clickeables y la planilla de campos (especificación de la Fase 3) |
| [`docs/04-mejoras.md`](docs/04-mejoras.md) | Cada mejora sobre el original, con su porqué y si necesita aprobación |
| [`docs/adr/`](docs/adr/) | Decisiones de arquitectura |
| [`referencia/`](referencia/) | Sitio original del cliente, logo y las seis ilustraciones SVG |

## Stack

Laravel 13 · PHP 8.3+ · MySQL 8 · Blade · Livewire 4 · Alpine.js · Tailwind CSS v4 · Vite ·
Pest · Playwright. Despliegue en Plesk de webparaguay.

## Estado

**Fase 0 — descubrimiento.** No se escribe código de producción hasta cerrar el inventario del
repo de Dante y las decisiones abiertas del legajo (§8).
