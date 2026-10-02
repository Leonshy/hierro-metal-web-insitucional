# Planilla de campos por plantilla

> Entregable de la Fase 1. **Es la especificación de la Fase 3** (backend y panel): lo que el panel tiene que
> permitir editar, con su límite. Los presupuestos de palabras salen de **medir el contenido real del cliente**
> (`docs/01`), no de un criterio genérico. Modelo de datos en `docs/03` §3, con los cambios de §9 de este documento.
> Se congela cuando el cliente apruebe el Hito 1.

**Cómo leerla.** *Presupuesto* = lo que mide hoy el contenido del cliente (mínimo–máximo, en palabras).
*Límite* = tope duro que valida el panel (≈ 1,3 × el máximo observado, para dejar margen sin romper el diseño).
Req. = obligatorio. Los textos van con voseo y sin HTML salvo donde se indica.

---

## 1. Configuración global (una sola ficha)

| Campo | Tipo | Req. | Límite | Nota |
|---|---|---|---|---|
| `razon_social` | texto | sí | 80 caracteres | Hierro Metal S.R.L. |
| `descripcion_corta` | texto | sí | 120 car. | Pie y metadescripción |
| `direccion_corta` / `direccion_larga` | texto | sí | 60 / 160 car. | Barra superior y Ubicación |
| `telefono_ventas` | texto | sí | 30 car. | Formato visible: +595 981 320 675 |
| `whatsapp_numero` | solo dígitos | sí | 15 | De ahí salen `wa.me` y `tel:` |
| `email_contacto` | email | sí | 150 car. | `hierrometalventas@hotmail.com` |
| `email_notificacion_cotizaciones` | email(s) | sí | 3 correos | Casilla que recibe los avisos; puede diferir de `email_contacto` |
| `maps_url` | URL | sí | 300 car. | Enlace directo; se usa también para la fachada del mapa |
| `maps_embed_url` | URL | no | 600 car. | Se carga sólo con consentimiento |
| `instagram`, `facebook` | URL | no | 200 car. | Barra superior y Ubicación |
| `aviso_numero_unico` | texto | no | 45 palabras | Se muestra en Contacto y Ubicación |
| `catalogo_pdf` | archivo PDF ≤ 20 MB | no | — | Sin archivo ⇒ el botón se oculta en todo el sitio |
| `catalogo_texto_boton` | texto | no | 4 palabras | «Descargar catálogo» |
| `catalogo_vigencia` | fecha | no | — | |

**Horarios** (lista, ordenable): `etiqueta` (≤ 30 car.), `dias`, `abre`, `cierra`, `cerrado` (bool). Alimenta «Abierto ahora»,
la pregunta de horario de la FAQ y el schema.org. No calcula feriados.

**Mensajes de WhatsApp** (lista): `contexto` (inicio, productos, familia, servicios, calidad, faq, ubicacion, contacto, gracias), `mensaje`
(≤ 20 palabras; admite `{familia}`). Valores iniciales en `docs/07` §3.6.

**Cookies y medición:** `texto_banner` (≤ 40 palabras), IDs de GA4/GTM, Pixel y token de Meta, activar/desactivar cada uno (D5).

## 2. Página: campos comunes a toda plantilla

Cada plantilla tiene su encabezado editable (no fijo en Blade):

| Campo | Tipo | Req. | Presupuesto | Límite |
|---|---|---|---|---|
| `rotulo` | texto | no | 1–3 palabras | 25 car. |
| `h1` | texto | sí | 3–8 palabras | 70 car. |
| `bajada` | texto | no | 12–53 | 60 palabras |
| `seo_titulo` | texto | sí | ≤ 60 car. | 60 car. |
| `seo_descripcion` | texto | sí | 90–160 car. | 160 car. |
| `canonical`, `noindex` | URL / bool | no | — | |

## 3. Inicio (`/`)

| Bloque | Campos | Presupuesto | Límite |
|---|---|---|---|
| Portada | `insignia`, `h1`, `bajada`, `nota`, 2 CTA (texto+destino) | insignia 5 palabras · h1 5–8 · bajada 40 · nota 8 | 8 / 10 / 55 / 12 palabras |
| Diferenciales (4) | `titulo`, `texto` | título 2–3 · texto 4–5 | 5 / 8 palabras |
| 01 Productos | `rotulo`, `titulo`, `bajada` | título 3 · bajada 28 | 6 / 40 |
| 02 Servicios | `titulo`, `bajada`, hasta 3 destacados (`titulo_home`, `resumen_home`) | título 6 · bajada 22 · resumen 8–10 | 8 / 30 / 14 |
| 03 Calidad | `titulo`, `bajada`, texto del botón | título 8 · bajada 14 | 12 / 22 |
| 04 Contacto | `titulo`, `bajada`, 2 CTA | título 3 · bajada 20 | 6 / 30 |

Las fichas de familia salen del módulo **Familias** (`resumen_home`). La ficha del catálogo sale de la Configuración.

## 4. Productos (`/productos`) y ficha de familia (`/productos/{slug}`)

**Familia** (5, ordenables):

| Campo | Tipo | Req. | Presupuesto | Límite |
|---|---|---|---|---|
| `slug` | único | sí | — | 40 car. (`chapas`, `perfiles`, `tubos`, `varillas`, `accesorios`) |
| `nombre` | texto | sí | 2–4 palabras | 6 palabras |
| `resumen_home` | texto | sí | 13–16 | 22 palabras |
| `bajada` | texto | sí | 20–30 | 40 palabras |
| `ilustracion` | enum | sí | chapas, perfiles, tubos, varillas, accesorios, ninguna | — |
| `imagen_id` | medios | no | opcional (D6) | — |
| `mensaje_whatsapp` | texto | no | 6–10 | 20 palabras; por defecto «Hola, quiero cotizar {nombre}.» |
| `seo_titulo`, `seo_descripcion` | texto | sí | ver §2 | 60 / 160 car. |
| `orden`, `activo` | | | | |

**Línea** (16, dentro de una familia, ordenables):

| Campo | Tipo | Req. | Presupuesto | Límite |
|---|---|---|---|---|
| `nombre` | texto | sí | **1–10 palabras** (mediana 5) | **24 palabras** |
| `descripcion` | texto | sí | 9–18 | 25 palabras |
| `usos` | etiquetas | sí | **2–3** etiquetas | máx. 4, cada una ≤ 3 palabras |
| `medidas` | tablas | no | ver abajo | — |
| `nota_medidas` | texto | no | 6–14 | 25 palabras |
| `activo`, `orden` | | | | |

> **Ajuste respecto del plan:** el plan decía «título de línea ≤ 6 palabras». El contenido real llega a 10
> («Tubos estructurales con y sin costura · SCH10 a SCH80»), así que el límite es **24** (decisión de Leonshy, para que el
> cliente pueda nombrar líneas largas) y el diseño admite que el nombre ocupe varios renglones.

**Medidas** (D2 = sí): una línea puede tener **varias tablas** (Laminadas en frío y en caliente tiene dos). Cada tabla:
`titulo` (≤ 4 palabras, opcional), `columnas` (2–6, ≤ 3 palabras c/u), `filas` (hasta 40; ≤ 60 caracteres por celda),
`modo` (`tabla` o `lista`: «lista» deja que el texto largo salte de renglón, como en inoxidables y galvanizadas).
El editor admite pegar desde Excel. Sin tablas, la ficha muestra «Consultanos las medidas disponibles y el precio.»

## 5. Servicios (`/servicios`)

| Módulo | Campos | Presupuesto | Límite |
|---|---|---|---|
| Encabezado | rótulo, h1, bajada, 2 CTA | h1 4 · bajada 38 | 8 / 55 |
| **Servicio** (6) | `nombre`, `descripcion`, `usos` (2–4), `destacado_home`, `titulo_home`, `resumen_home` | nombre 1–4 · descripción **14–33** · usos 2–4 | 6 / 45 / 4 |
| Título del bloque | `titulo_bloque` | 3–4 | 6 |
| **Pasos** (4) | `titulo`, `texto` | título 1–4 · texto **12–18** | 6 / 25 |
| Título de pasos | `titulo`, `bajada` | 5 · 12 | 8 / 20 |
| Calidad (franja) | `titulo`, `bajada`, botón | 10 · 20 | 14 / 30 |

## 6. Calidad (`/calidad`) y Privacidad (`/privacidad`)

Páginas de texto enriquecido (bloques de Dante). Calidad: encabezado, introducción (1 párrafo), **Compromisos** (4: `titulo` 2–4 palabras,
`texto` 19–23, límites 6 y 30), «Qué significa esto para tu obra» (3 subtítulos con 1 párrafo cada uno) y «Ámbito de aplicación»
(1 párrafo). Privacidad: una sola página de texto saneado (listas, enlaces, negritas); **sin publicar hasta la revisión legal**.

## 7. Preguntas frecuentes (`/preguntas-frecuentes`)

| Campo | Tipo | Req. | Presupuesto | Límite |
|---|---|---|---|---|
| `pregunta` | texto | sí | 2–8 palabras | 12 |
| `respuesta` | texto saneado (párrafos, enlaces) | sí | **21–55** | 80 palabras |
| `orden`, `activa` | | | | |

La pregunta de horario se renderiza desde **Horarios**; la de ubicación y la de cotización usan los datos de Configuración (no se duplican).

## 8. Ubicación, Contacto, Gracias y 404

| Plantilla | Campos propios | Notas |
|---|---|---|
| **Ubicación** | rótulo, h1 (5), bajada (24), 2 CTA, título y nota del mapa, franja amarilla (`titulo`, `texto`, CTA) | Dirección, horarios, redes y «Abierto ahora» salen de Configuración |
| **Contacto** | rótulo, h1 (3), bajada (40), título del formulario, texto de consentimiento, texto del botón, canales directos | Textos de error y de éxito del formulario: ver `docs/07` §3.2 (editables) |
| **Rubros** (9, ordenables) | `nombre` (≤ 6 palabras), `familia_id` o `servicio_id` (opcional), `activo` | El rubro viaja en `?rubro=` desde la familia |
| **Gracias** | rótulo, h1, bajada (≤ 30), 3 pasos, texto de apuro, 3 botones | `noindex` |
| **404** | h1, bajada (≤ 25), 2 botones | |

## 9. Cotizaciones (bandeja del panel)

Campos del formulario: `nombre` (req., 120 car.), `ci_ruc` (req., CI `1.234.567` o RUC `80012345-6`; agregado en octubre 2026), `empresa` (120), `telefono` (req., 40), `email` (150), `rubro` (lista), `mensaje` (req., 4000),
adjuntos (hasta 3 · 10 MB · `pdf, jpg, jpeg, png, webp, dwg, dxf, xlsx`), `consentimiento` (req.), honeypot `sitio_web`.
Se guardan además `origen` (página), `utm`, `ip`, `user_agent`, `estado`, `asignado_a`, `notas_internas`. Detalle en `docs/03` §3.4.

---

## 10. Cambios al modelo de datos de `docs/03` por las decisiones posteriores

| # | Cambio | Por qué |
|---|---|---|
| 1 | `lineas.medidas` pasa de **una** tabla a una **lista de tablas** (`titulo`, `columnas`, `filas`, `modo`) y se agrega `nota_medidas` | Laminadas en frío y caliente es una línea con dos tablas; inoxidables y galvanizadas son listas de texto largo |
| 2 | `familias.mensaje_whatsapp` y tabla `mensajes_whatsapp` (por contexto) | WhatsApp con mensaje según la página (M6) |
| 3 | Configuración: `email_contacto` separado de `email_notificacion_cotizaciones`, `maps_embed_url`, texto y activación de cookies | Correo del catálogo y banner D5 |
| 4 | Módulo **Vendedores** (`nombre`, `telefono`, `activo` = **falso** por defecto) | Decisión 10: se cargan pero no se publican |
| 5 | `paginas`: `seo_titulo`, `seo_descripcion`, `noindex` por plantilla; plantillas Gracias y 404 | Metadatos de `docs/07` §3.8 |
| 6 | Textos del formulario (errores y estados) editables desde el panel | `docs/07` §3.2 |

## 11. Pendiente de aprobación

Esta planilla se congela con el Hito 1. Si el cliente cambia estructura (por ejemplo, más de 5 familias o más de 3 servicios destacados en la home),
se ajusta acá antes de tocar el backend.
