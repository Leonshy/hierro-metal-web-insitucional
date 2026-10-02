# PLAN — Web Institucional Hierro Metal S.R.L.

> Documento operativo. Alcance, negocio y riesgos en `docs/00-legajo-tecnico.md`.
> Acá está el **cómo** y el **en qué orden**. Versión 1.0 — Octubre 2026.

---

## Estado al 2026-10-02

Construcción terminada y **desplegada en staging** (`https://hierrometal.webparaguay.com`). Foto completa, con lo
pendiente y lo no verificado, en `docs/09-estado-del-proyecto.md`.

| Fase | Estado |
|---|---|
| 0 Descubrimiento · 1 UX · 2 Copy y diseño | Hecho (insumos en `docs/`) |
| 3 Backend y panel · 4 Frontend · 5 Contenido y catálogo | Hecho. El panel se reorganizó por secciones (con Publicada/Borrador y vista previa) y se sumaron páginas legales dinámicas y el CI/RUC en el formulario |
| 6 SEO e integraciones · 7 Rendimiento | Hecho. Falta cargar IDs de GTM/Meta y coordenadas |
| 8 Seguridad | Hecho en código (cabeceras, Turnstile, anti-spam, adjuntos, auditoría, respaldos). **Pendiente:** pentest con `strix` y prueba de restauración de un respaldo |
| 9 QA | Pest (517), PHPStan, Pint y Lighthouse en verde. **Pendiente:** E2E con Playwright y recepción real del correo por el cliente |
| 10 Despliegue y cutover | Staging hecho. **Pendiente:** producción, capacitación, manual del panel, monitoreo 30 días |

Los checkboxes de abajo son el plan original (histórico); no se fueron tildando.

---

## La regla que ordena todo el plan

**Mismo proceso que Dante: cada etapa produce el insumo de la siguiente, y ninguna arranca sin
la anterior aprobada.**

```
UX  ──────►  COPY  ──────►  BACKEND  ──────►  FRONTEND
 │             │               │                 │
 │             │               │                 └─ se maqueta con el contenido real del inventario
 │             │               └─ el esquema sale de los campos del inventario, no se adivina
 │             └─ se pule contra campos definidos y presupuestos de palabras
 └─ define páginas, plantillas, campos y el flujo hasta la cotización
```

**Diferencia con Dante:** acá el cliente ya escribió el contenido y ya eligió una estética. Eso
acorta las fases 1 y 2 — no se parte de cero, se parte de `docs/01-inventario-contenido.md` y
`docs/02-sistema-diseno.md`. Lo que se aprueba en cada hito son **las mejoras sobre su versión**,
no la versión entera de nuevo.

**Tres hitos de aprobación del cliente. Ni uno más.** Cada hito se aprueba entero, de una vez.
**Plazo: 5 días hábiles por hito.** Vencido sin observaciones, se considera aprobado. Va en el
contrato.

---

## Mapa de fases

| Fase | Nombre | Horas | Hito de cliente |
|---|---|---|---|
| 0 | Descubrimiento y cierre de alcance | 6–8 | — |
| **1** | **UX: organización y flujo** | **8–12** | 🚩 **HITO 1** |
| **2** | **Copy pulido y sistema de diseño** | **10–14** | 🚩 **HITO 2** |
| 3 | Backend y panel (adaptación de Dante) | 24–34 | — (interna) |
| **4** | **Frontend** | **24–32** | 🚩 **HITO 3** |
| 5 | Carga de contenido y catálogo | 4–8 | — |
| 6 | SEO e integraciones | 6–8 | — |
| 7 | Rendimiento | 4–6 | — |
| 8 | Seguridad | 6–8 | — |
| 9 | QA y testing | 8–12 | — |
| 10 | Despliegue, capacitación y cutover | 4–6 | Entrega |
| | **Total** | **104–148 h** | |

---

## Fase 0 — Descubrimiento y cierre de alcance

**Objetivo:** que no quede ninguna incógnita que pueda mover el precio o romper el cutover.

**Tareas**

- [ ] **Inventariar el repo de Dante** y completar `docs/03-backend-desde-dante.md` §1 con lo que
      realmente hay: versión de Laravel, paquetes, módulos del panel, cómo resuelve bloques,
      medios, SEO, traducciones, formularios, auditoría, respaldos. **No asumir: leer.**
- [ ] Decidir la estrategia de arranque: fork del repo de Dante limpio vs. proyecto nuevo con
      módulos copiados (ADR 0001 propone fork limpio; confirmar después del inventario).
- [ ] Confirmar con el cliente las decisiones abiertas del legajo §8 (D1–D7).
- [ ] Relevar dominio y DNS: ¿`hierrometal.com` es el dominio? ¿Hay sitio publicado hoy? Si hay,
      inventariar sus URLs para el mapa 301 y bajar la línea base de Search Console.
- [ ] Relevar correo: dónde está el MX de `hierrometal.com`, qué casilla recibe las cotizaciones,
      SPF/DKIM para que el mail del formulario no caiga en spam.
- [ ] Relevar el Plesk destino: versión de PHP, Node para build, cron, cola, límite de subida.
- [ ] Pedir al cliente: **logo en vector**, **catálogo PDF vigente**, fotos propias si existen
      (depósito, taller, flota, material), y quién aprueba.

**Hecho cuando:** `docs/03` §1 completo y verificado contra el código; D1–D7 respondidas o con
default aceptado por escrito; insumos pedidos con fecha comprometida.

---

## Fase 1 — UX: organización y flujo · 🚩 HITO 1

**Objetivo:** validar la estructura del original, corregir lo que traba la cotización y fijar los
campos de cada plantilla.

**Tareas**

- [ ] Con `ux-flow-designer`: mapa del sitio (CLAUDE.md §4), flujo principal
      **llegar → ver familia → pedir cotización / WhatsApp**, y flujo secundario
      **llegar desde Google con una búsqueda de producto → ficha de familia → cotizar**.
- [ ] Revisar el original contra ese flujo. Ya identificado (detalle en `docs/04-mejoras.md`):
      - "Ver medidas →" no lleva a ninguna medida (M3).
      - El rubro elegido en el formulario no viaja a WhatsApp (M6).
      - Las páginas internas no se pueden compartir ni indexar (M1).
- [ ] Wireframes de cada plantilla: home, productos, ficha de familia, servicios, calidad, FAQ,
      ubicación, contacto, gracias, privacidad. Móvil primero.
- [ ] **Planilla de campos por plantilla**, con presupuesto de palabras tomado del contenido real
      (ej.: título de línea ≤ 6 palabras; descripción 12–25; usos 2–4 etiquetas).

**Entregable del hito:** mapa del sitio + flujos + wireframes móvil y escritorio + lista de
mejoras de estructura (M1–M6) para aprobar.

**Hecho cuando:** el cliente aprueba el Hito 1. La planilla de campos queda congelada: es la
especificación de la Fase 3.

---

## Fase 2 — Copy pulido y sistema de diseño · 🚩 HITO 2

**Objetivo:** dejar todo el texto final y el sistema visual listos para maquetar.

**Copy**

- [ ] Partir de `docs/01-inventario-contenido.md`. Conservar voz y voseo.
- [ ] Ediciones mínimas y justificadas, cada una registrada en `docs/04-mejoras.md` §Copy.
- [ ] Escribir **sólo** lo que falta: textos de la ficha de familia (si D2 lo habilita), página de
      gracias, mensajes de error y éxito del formulario, metatítulos y metadescripciones,
      textos alternativos de las ilustraciones.
- [ ] Revisar la política de privacidad con el cliente (ver R5 del legajo — referencia legal).

**Sistema de diseño**

- [ ] `/impeccable init` con los tokens de `docs/02-sistema-diseno.md`. Ningún color literal.
- [ ] Redibujar el logo en vector si el cliente no lo entrega (D4).
- [ ] Componentes: botones (amarillo, negro, línea, línea amarilla), rótulo, insignia, ficha,
      retícula, lista de líneas, pasos, compromisos, datos de contacto, horarios, acordeón FAQ,
      campo de formulario, aviso, WhatsApp flotante, barra superior, cabecera, pie.
- [ ] Estados que el original no define: hover, foco, activo, deshabilitado, error, cargando,
      éxito. Documentar y aprobar.
- [ ] Alta fidelidad de home, ficha de familia y contacto (las tres que más pesan en conversión).
      El resto se aprueba sobre el sistema.

**Entregable del hito:** copy final de todas las páginas + sistema de diseño + tres pantallas en
alta fidelidad.

**Hecho cuando:** el cliente aprueba el Hito 2.

---

## Fase 3 — Backend y panel (adaptación de Dante) · interna

**Objetivo:** el panel de Dante funcionando para Hierro Metal, con los módulos de su dominio.

**Tareas**

- [ ] Arranque según ADR 0001. Renombrar app, limpiar contenido y configuración de Dante.
- [ ] Apagar multiidioma: locale fijo `es`, controles de idioma ocultos (CLAUDE.md regla 7).
- [ ] Quitar o desactivar módulos de Dante que no aplican (ver `docs/03` §2).
- [ ] Módulos nuevos (`docs/03` §3): **Familias y líneas**, **Servicios y pasos**,
      **Compromisos de calidad**, **FAQ**, **Diferenciales**, **Horarios**, **Cotizaciones**,
      **Catálogo PDF**, configuración de contacto y rubros del formulario.
- [ ] Bandeja de cotizaciones: estados, notas internas, asignado, adjuntos, exportación CSV,
      contador de "nuevas" en el panel.
- [ ] Notificación por correo en cola, con el pedido persistido primero (regla 8).
- [ ] Seeders desde `docs/01-inventario-contenido.md`: el sitio arranca con el 100 % del contenido.
- [ ] Tests Pest de cada módulo y del flujo de cotización (incluye: falla SMTP ⇒ cotización
      igual guardada).

**Hecho cuando:** todo el contenido del inventario se edita desde el panel; los tests pasan;
la cotización sobrevive a un SMTP caído.

---

## Fase 4 — Frontend · 🚩 HITO 3

**Objetivo:** el sitio completo, fiel al original y mejorado, en staging.

**Tareas**

- [ ] Layout: barra de datos, cabecera sticky, menú móvil, pie, WhatsApp flotante, salto al
      contenido. Igual al original, con los estados de la Fase 2.
- [ ] Plantillas de todas las rutas de CLAUDE.md §4, alimentadas desde el panel.
- [ ] Ilustraciones SVG del original como componentes Blade, con tokens en lugar de colores
      literales. Animación de entrada de la ilustración de portada (secciones que "se apoyan" en
      el piso amarillo) con `emil-design-eng`, desactivada en `prefers-reduced-motion`.
- [ ] Formulario Livewire: validación en vivo, adjuntos, envío sin recarga, redirección a
      `/contacto/gracias`. Rubro preseleccionado si se llega desde una familia (`?rubro=chapas`).
- [ ] Botón de WhatsApp con mensaje precargado según la página o el rubro (M6).
- [ ] Mapa con fachada de consentimiento (ADR 0003).
- [ ] Descarga del catálogo PDF real (M2).

**Entregable del hito:** staging navegable en móvil y escritorio.

**Hecho cuando:** el cliente aprueba el Hito 3.

---

## Fase 5 — Carga de contenido y catálogo

- [ ] Verificar que los seeders cargaron todo el inventario; corregir con el copy final del Hito 2.
- [ ] Cargar catálogo PDF, logo vectorial, fotos si las hay.
- [ ] Si D2 = sí: cargar las tablas de medidas por línea desde el catálogo del cliente.

**Hecho cuando:** cero textos de relleno; cada página coincide con el copy aprobado.

## Fase 6 — SEO e integraciones

- [ ] Metadatos por página desde el panel; Open Graph con imagen generada por familia.
- [ ] schema.org: `HardwareStore` (o `LocalBusiness`) con dirección, teléfono, horarios y
      geolocalización; `FAQPage` en preguntas frecuentes; `BreadcrumbList` en fichas.
- [ ] `sitemap.xml`, `robots.txt`, canonical.
- [ ] Mapa 301 si hay sitio previo (Fase 0).
- [ ] Integraciones según D5: GTM/GA4 y Meta Pixel + Conversions API, bloqueados hasta
      consentimiento; evento de conversión = llegada a `/contacto/gracias` y clic en WhatsApp.
- [ ] Google Business Profile: verificar que la ficha coincide con dirección, teléfono y horario
      del sitio (NAP consistente). Tarea de cliente, guiada por nosotros.

## Fase 7 — Rendimiento

- [ ] Fuentes autohospedadas en `woff2`, subset latino, `font-display: swap`, precarga de la
      condensada.
- [ ] SVG inline optimizados (SVGO), logo vectorial, imágenes en WebP/AVIF con tamaños.
- [ ] Caché de respuesta invalidada al publicar (patrón Dante).
- [ ] Objetivos: Lighthouse móvil ≥ 95, LCP < 2.0 s en 4G, CLS < 0.05, INP < 200 ms.

## Fase 8 — Seguridad

- [ ] Panel en ruta no adivinable, 2FA obligatorio, roles admin/editor (patrón Dante).
- [ ] Cabeceras de seguridad y CSP; subida de adjuntos según regla 10.
- [ ] Pentest con `strix` contra staging. **Cero hallazgos Críticos/Altos** para pasar a producción.
- [ ] Respaldos con `spatie/laravel-backup` a almacenamiento externo; **una restauración probada**.

## Fase 9 — QA y testing

- [ ] Pest: cobertura de todas las rutas del panel y del sitio.
- [ ] Playwright: flujo de cotización completo en móvil (360 px) y escritorio, con adjunto;
      WhatsApp; descarga de catálogo; FAQ con teclado; mapa con consentimiento.
- [ ] Accesibilidad WCAG 2.1 AA: contraste de cada par de tokens, foco visible, navegación por
      teclado, lector de pantalla en formulario.
- [ ] **Envío real de prueba recibido por el cliente** en su casilla.

## Fase 10 — Despliegue, capacitación y cutover

- [ ] Producción en Plesk con SSL. Cutover cambiando **sólo** el registro web; MX intactos.
- [ ] Manual corto del panel enfocado en las tres tareas reales: **atender cotizaciones,
      actualizar el catálogo PDF, editar una línea o una pregunta frecuente**.
- [ ] Capacitación presencial (una hora alcanza).
- [ ] Monitoreo 30 días: Search Console, entregabilidad del correo, spam.
- [ ] Extraer el **patrón "web de proveedor industrial con catálogo y cotización"** (ver legajo §4).
