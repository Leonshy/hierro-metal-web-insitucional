# CLAUDE.md — Web Institucional Hierro Metal S.R.L.

> Contexto permanente del repo. Leer completo antes de cualquier tarea.
> Plan operativo: `PLAN.md`. Alcance y negocio: `docs/00-legajo-tecnico.md`.

---

## 1. Qué es este proyecto

Sitio institucional de **Hierro Metal S.R.L.** (importación y venta mayorista/minorista de chapas,
perfiles, tubos, varillas y accesorios de acero, más servicios de taller: corte, plegado,
perforado, fabricación de perfiles C/U, galvanización y entrega en obra con flota propia).
Depósito y casa matriz en Fernando de la Mora, Central, Paraguay.

**El objetivo de negocio del sitio es uno solo: generar pedidos de cotización.** Todo lo demás
(catálogo, calidad, FAQ, ubicación) existe para que el visitante llegue al formulario o a
WhatsApp con confianza y con su pedido bien armado.

Tres insumos:

1. **`referencia/sitio-cliente-original.html`** — el sitio que armó el cliente: una sola página
   HTML con 8 "páginas" conmutadas por hash (`#/productos`, `#/contacto`…). Es la referencia de
   **contenido y aspecto gráfico**. Se respeta; se mejora; no se reinventa.
2. **El repo de Dante** (web institucional de webparaguay, Laravel, panel propio bilingüe ES/DE)
   — base del backend y del panel. Ruta local: `~/Dante-Web-Institucional/app` (Laravel 13.17 + **Filament 5**; ver `docs/03` §1).
3. **`docs/01-inventario-contenido.md`** — el contenido del sitio del cliente ya extraído y
   ordenado. Los seeders salen de ahí.

## 2. Reglas no negociables

### Fidelidad al original

1. **El contenido del cliente es la fuente de verdad.** Ningún texto se inventa. Toda edición de
   copy se registra en `docs/04-mejoras.md` y pasa por el Hito 2.
2. **Se conserva el voseo** del original ("pedí", "mandanos", "escribinos"). Es la voz del cliente.
3. **El aspecto gráfico se conserva:** amarillo institucional sobre negro, Barlow Condensed en
   mayúsculas para títulos, IBM Plex Mono para rótulos, retículas de 1 px, botones rectos sin
   radio con borde inferior de 3 px, ilustraciones SVG de secciones de acero. Ver
   `docs/02-sistema-diseno.md`. **Mejorar no es cambiar de estética.**
4. **Las seis ilustraciones SVG del original se reutilizan** (`referencia/assets/`). Se pueden
   refinar y animar; no se reemplazan por fotos de stock ni por iconos genéricos.

### Antes de asumir nada de Dante

5. **Leer el repo de Dante antes de proponer arquitectura.** Dante es bilingüe y su panel ya
   resuelve más de lo que dice su legajo viejo. Cualquier afirmación sobre "cómo lo hace Dante"
   se verifica contra el código y se documenta en `docs/03-backend-desde-dante.md`.
6. **Reutilizar, no reescribir.** Si Dante ya lo resuelve (auth, 2FA, roles, medios, menús, SEO,
   redirecciones, auditoría, formularios, respaldos), se copia y se adapta. Construir desde cero
   algo que Dante ya tiene requiere justificación escrita.
7. **La capa de traducción de Dante no se arranca: se apaga.** Hierro Metal es sólo español.
   Se deja el locale fijo en `es` y se ocultan los controles de idioma en el panel, para que un
   segundo idioma futuro sea configuración y no reconstrucción.

### Formulario de cotización — el KPI que manda

8. **El pedido se persiste en MySQL antes de intentar el correo.** El envío de mail va en cola
   (o en `try/catch` si la cola no está disponible en Plesk). Un problema de SMTP no puede costar
   un pedido. Mismo patrón que la landing de Tres Sesenta.
9. **Anti-spam en capas:** honeypot (el original ya trae `sitio_web`) + timestamp firmado +
   `throttle` por IP. Turnstile queda configurado y apagado; se prende desde el panel si aparece
   spam real.
10. **Adjuntos (plano/despiece):** lista blanca `pdf, jpg, jpeg, png, webp, dwg, dxf, xlsx`,
    máximo 10 MB por archivo y 3 archivos. Se guardan fuera de `public/`, se sirven sólo
    autenticado desde el panel. Nunca se ejecutan ni se renderizan inline sin sanitizar.

### Diseño y frontend

11. **Ningún color ni tipografía literal en componentes.** Todo sale de tokens (`@theme` de
    Tailwind v4 / custom properties). Regla de `impeccable` para todos los proyectos.
12. **Rutas reales, no hash.** Cada página del original tiene su URL propia, su `<title>`, su
    meta descripción y su canonical. Ver ADR 0002.
13. **Mobile-first.** El público es constructor, herrero o encargado de obra mirando desde el
    celular. Botones ≥ 48 px de alto, teléfono y WhatsApp a un toque desde cualquier página.
14. **Motion con propósito y `prefers-reduced-motion` respetado siempre.** Revelados suaves,
    nada que demore la lectura del precio o del teléfono.
15. **Fuentes autohospedadas** (Barlow Condensed, IBM Plex Sans, IBM Plex Mono — licencia OFL).
    Cero llamadas a Google Fonts en runtime.

### Terceros y privacidad

16. **Google Maps se carga con fachada de consentimiento** (clic para cargar). Coherente con la
    política de privacidad del propio cliente. Ver ADR 0003.
17. **Analytics/Meta, si entran, quedan bloqueados hasta consentimiento** y la política de
    privacidad se actualiza en la misma entrega. El original dice "no instala cookies": no se
    puede publicar una cosa y hacer otra.

### Operación

18. **Sin PII real en desarrollo ni en tests.** Factories con datos ficticios.
19. **Playwright y strix sólo contra entornos propios** (local y staging). Nunca contra producción
    sin autorización escrita.
20. **Toda herramienta o paquete nuevo pasa por `skill-security-auditor`** antes de instalarse.

## 3. Stack

| Capa | Tecnología |
|---|---|
| Framework | Laravel 13 (el mismo major que Dante — si difiere, manda Dante) |
| Lenguaje | PHP 8.3+ |
| Base | MySQL 8 |
| Vistas | Blade + Livewire (islas: formulario, menú móvil, filtros de catálogo) |
| JS | Alpine.js |
| CSS | Tailwind CSS v4 con tokens en `@theme` |
| Build | Vite |
| Panel | El de Dante: **Filament 5** + spatie/permission + spatie/activitylog (2FA por email, opt-in en Dante; acá se fuerza) |
| Tests | Pest (unit/feature) + Playwright (E2E) |
| Despliegue | Plesk de webparaguay, PHP-FPM, Let's Encrypt |
| Correo saliente | SMTP del servidor o del dominio del cliente — **confirmar en Fase 0** |

## 4. Mapa del sitio (rutas)

| Ruta | Origen en el sitio del cliente | Plantilla |
|---|---|---|
| `/` | `#/inicio` | home |
| `/productos` | `#/productos` | listado de familias |
| `/productos/{familia}` | anclas `#chapas`, `#perfiles`… | **nueva**: ficha de familia (ver `docs/04-mejoras.md` M3) |
| `/servicios` | `#/servicios` | servicios |
| `/calidad` | `#/calidad` | prosa + compromisos |
| `/preguntas-frecuentes` | `#/faq` | FAQ |
| `/ubicacion` | `#/ubicacion` | ubicación + mapa |
| `/contacto` | `#/contacto` y `#/contacto/cotizar` | formulario |
| `/contacto/gracias` | — | **nueva**: confirmación (medible como conversión) |
| `/privacidad` | `#/privacidad` | legal |
| `/catalogo.pdf` (o `/descargas/catalogo`) | botón "Descargar PDF" | descarga del PDF cargado en el panel |

## 5. Convenciones

- Código, tablas y columnas en **español** cuando son conceptos del dominio (`familias`,
  `lineas`, `cotizaciones`), igual que Dante. Infraestructura de Laravel en inglés.
- Commits en español, en imperativo: `Agrega módulo de familias al panel`.
- Una fase = una rama `fase-NN-nombre`. Merge a `main` sólo con la definición de "hecho" cumplida.
- Cada decisión que se aparte de este documento se registra como ADR en `docs/adr/`.

## 5b. Glosario del dominio

| Término | Significado en este proyecto |
|---|---|
| Familia | Agrupación del catálogo: Chapas, Perfiles, Tubos, Varillas, Accesorios |
| Línea | Producto dentro de una familia (p. ej. "Chapas galvanizadas") |
| Usos | Etiquetas cortas de aplicación de una línea ("Techos · Galpones") |
| Servicio | Trabajo de taller o logística (corte, plegado, perforado, perfiles, galvanización, entrega) |
| Cotización | Pedido enviado por el formulario. Es el lead del sitio |
| Rubro | Lo que el visitante elige en "¿Qué necesitás?" del formulario |
| Despiece | Lista de piezas con medidas y cantidades que manda el cliente |

## 6. Herramientas del entorno

| Herramienta | Uso en este proyecto |
|---|---|
| `ux-flow-designer` | Fase 1: flujo "llegar → elegir → cotizar", wireframes de las plantillas |
| `impeccable` | Fase 2 y 4: `/impeccable init` con los tokens de `docs/02-sistema-diseno.md` |
| `emil-design-eng` | Fase 4: motion (revelados, menú, acordeón, envío del formulario) |
| `context7` | Docs actualizadas de Laravel 13, Livewire 4, Tailwind v4 |
| `playwright-cli` | Fase 9: E2E del flujo de cotización en móvil y escritorio |
| `strix` | Fase 8: pentest contra staging |
| Subagentes "The Agency" | Revisión de copy (marketing), accesibilidad (testing), seguridad |
