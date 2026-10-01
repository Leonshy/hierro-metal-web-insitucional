# 04 — Mejoras sobre el sitio del cliente

> "Lo más fiel posible, pero mejorado." Cada mejora tiene su porqué y dice si necesita
> aprobación del cliente. Las **técnicas** son invisibles para el visitante y no se discuten con
> el cliente; las **de estructura y copy** entran en el hito correspondiente.

---

## Lectura del original

El sitio del cliente está bien pensado: copy concreto y con voz propia, estructura clara
orientada a la cotización, buena accesibilidad de base (salto al contenido, `aria-*`, foco
visible, `prefers-reduced-motion`), honeypot en el formulario y una política de privacidad seria.
**Es una vista previa estática**: una sola página que simula ocho con un enrutador por hash, con
el formulario, el mapa y la descarga del catálogo sin funcionar. El trabajo no es rehacerlo: es
**convertirlo en un sitio real, administrable y que convierta**.

---

## Mejoras técnicas (sin aprobación del cliente)

| # | Mejora | Por qué |
|---|---|---|
| T1 | **Panel autoadministrable** (backend de Dante) | Hoy cambiar un horario o una línea exige tocar HTML |
| T2 | **Formulario real** con persistencia antes del mail, cola, reintento y bandeja en el panel | Un pedido perdido es plata perdida. Mismo patrón que Tres Sesenta |
| T3 | **Fuentes autohospedadas** | Rendimiento y privacidad: el original llama a Google Fonts |
| T4 | **SVG con tokens, optimizados** | Regla de `impeccable`; menos peso |
| T5 | **Contraste del gris** `#837f76` → `#706c64` | El original no pasa AA en rótulos y ayudas (3.99:1) |
| T6 | **schema.org** `HardwareStore` + `FAQPage` + `BreadcrumbList`, sitemap, canonical | Aparecer bien en Google Maps y en búsquedas locales ("chapas Fernando de la Mora") |
| T7 | **Caché de respuesta, imágenes modernas, CWV en verde** | El público mira desde el celular en obra, con 4G |
| T8 | **Seguridad**: 2FA, CSP, adjuntos en disco privado, strix, respaldos | Estándar webparaguay |

## Mejoras de estructura y UX (Hito 1)

| # | Mejora | Por qué | Original |
|---|---|---|---|
| M1 | **URLs reales por página** (`/productos`, `/servicios`…) | El enrutador por hash hace que Google vea una sola página y que no se pueda compartir un enlace a "chapas". Ver ADR 0002 | `#/productos` |
| M2 | **Catálogo PDF que se descarga de verdad**, reemplazable desde el panel, con URL fija | Hoy el botón muestra un aviso | botón sin destino |
| M3 | **Ficha propia por familia** (`/productos/chapas`…) con sus líneas, y tabla de medidas si el cliente las provee (D2) | Las fichas de la home dicen **"Ver medidas →"** y la página de productos **no muestra ninguna medida**. Promesa rota en el CTA más visible. Además, cada ficha es una página indexable por búsqueda de producto | ancla dentro de una página larga |
| M4 | **Adjuntar plano o despiece** en el formulario (hasta 3 archivos) | El propio copy pide "mandanos el plano o el despiece", pero el formulario sólo acepta texto | sólo textarea |
| M5 | **Rubro preseleccionado** al llegar al formulario desde una familia o servicio | Un toque menos; el pedido llega mejor clasificado | select vacío |
| M6 | **WhatsApp con mensaje precargado según la página** ("Hola, quiero cotizar chapas…") | Llega al vendedor con contexto; se mide de qué página vino | mensaje vacío |
| M7 | **"Abierto ahora / Cerrado"** junto a los horarios | Responde la pregunta real de quien va a ir al depósito | horario fijo |
| M8 | **Página de gracias** tras el envío, con próximo paso y WhatsApp | Confirma al usuario y permite medir la conversión | mensaje en la misma página |
| M9 | **Mapa con fachada de consentimiento** (clic para cargar) | Coherente con su política de privacidad, y no carga Google si nadie lo mira. Ver ADR 0003 | sustituto de vista previa |
| M10 | **Logo vectorial** | El JPEG de 225 px se ve borroso en celulares | JPEG |

## Ajustes de copy (Hito 2)

| # | Dónde | Observación | Propuesta |
|---|---|---|---|
| C1 | Servicios — "Los cinco servicios" | La lista tiene **seis** servicios | "Los seis servicios", o "Qué hacemos con el material" (no envejece si se agrega uno) |
| C2 | Home — "Ver medidas →" y "Ver productos y medidas" | Sin medidas publicadas, el CTA promete algo que no hay | Si D2 = sí, queda. Si no: "Ver líneas →" y "Ver productos" |
| C3 | Privacidad §4 | Cita la **Ley N.º 6534/2020**, cuyo objeto son los datos personales **crediticios**; el sitio no trata datos crediticios | Revisión por el asesor legal del cliente antes de publicar. **No lo corregimos nosotros** |
| C4 | Privacidad §2 y §8 | Dice que no hay cookies propias ni medición. Si entran GA4/Meta (D5), es falso | Actualizar §2, §5 y §8 en la misma entrega que las integraciones |
| C5 | Privacidad §2 | Con adjuntos (M4), se recopila un dato nuevo | Agregar "archivos que adjuntes (planos, listas)" |
| C6 | Home — bloque 04 | La bajada termina con "Ventas se centraliza en un único número corporativo.", que suena a advertencia en un bloque de venta | Moverlo al aviso antifraude (ya existe en Contacto) y dejar la bajada en "…en el día." |
| C7 | Textos nuevos | Gracias, errores del formulario, metadescripciones, alt de ilustraciones, intro de cada ficha de familia | Se escriben en la Fase 2 con la voz del original (voseo, frases cortas, concretas) |

**Decisiones de copy (octubre 2026, ver `docs/07`):** C1 → «Los seis servicios» (K1). C2 → los CTA se mantienen: hay medidas (D2 resuelto). C3 → sin cambios, pendiente de revisión legal. C4 → pendiente de D5. C5 → aplicado (K5). C6 → aplicado (K2). C7 → escrito. Nuevos: K3 («También varilla roscada») y K4 (rótulo «Correo» en lugar de «Administración»).

**Todo lo demás del copy se publica tal cual.**

## Mejoras de diseño (Hito 2 y 3)

| # | Mejora |
|---|---|
| D-1 | Estados completos de cada componente (hover, foco, activo, error, cargando, éxito) |
| D-2 | Animación de entrada de la ilustración de portada y revelados al hacer scroll (emil) |
| D-3 | FAQ con animación de apertura; menú móvil con transición |
| D-4 | Ficha de familia: ilustración grande + líneas + tabla de medidas + CTA de cotización con rubro preseleccionado |
| D-5 | Espacio previsto para fotos propias del cliente (depósito, flota, taller) si las entrega, sin depender de ellas |
