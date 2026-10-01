# UX Flows — Web institucional Hierro Metal S.R.L.

> Entregable de la Fase 1 (Hito 1). Criterio rector: **mantenerse cerca del sitio que propuso el
> cliente** y corregir sólo lo que ayuda a sus tres objetivos: saber rápido qué vende, llegar al
> catálogo, hablar con ventas sin fricción.
> Entrada: `docs/01`, `docs/04`, `docs/05` y el sitio original. Casos de uso en [use-cases.md](use-cases.md).

**Planilla de campos por plantilla** (especificación de la Fase 3): [planilla-campos.md](planilla-campos.md)

## Mapa de pantallas

[diagrams/screen-map.md](diagrams/screen-map.md) · Prototipo clickeable: abrir
[wireframes/index.html](wireframes/index.html) en el navegador (375 px, sin JavaScript).

## Inventario de pantallas

| Pantalla | Para qué sirve | Wireframe | Casos de uso |
|---|---|---|---|
| Inicio `/` | Entender qué vende y llegar a catálogo, WhatsApp o cotización | [index](wireframes/index.html) | UC-001, 003, 004 |
| Menú móvil | Navegar con una mano | [menu](wireframes/menu.html) | UC-001 |
| Productos `/productos` | Ver las 5 familias y el catálogo | [productos](wireframes/productos.html) | UC-001, 002, 003 |
| Ficha de familia `/productos/{familia}` | Líneas, medidas y cotizar con rubro | [familia](wireframes/familia.html) | UC-002, 006 |
| Servicios `/servicios` | Corte, plegado, perforado, perfiles, galvanización, entrega | [servicios](wireframes/servicios.html) | UC-008 |
| Calidad `/calidad` | Compromisos y certificados | [calidad](wireframes/calidad.html) | UC-008 |
| Preguntas frecuentes | Resolver dudas | [faq](wireframes/faq.html) | UC-008 |
| Ubicación `/ubicacion` | Cómo llegar y abierto ahora | [ubicacion](wireframes/ubicacion.html) | UC-007 |
| Contacto `/contacto` | Formulario de cotización | [contacto](wireframes/contacto.html) | UC-005 |
| Gracias `/contacto/gracias` | Confirmar y medir la conversión | [gracias](wireframes/gracias.html) | UC-005 |
| Privacidad `/privacidad` | Texto legal | [privacidad](wireframes/privacidad.html) | UC-008 |
| Descarga de catálogo | Simula la descarga del PDF | [catalogo-pdf](wireframes/catalogo-pdf.html) | UC-003 |
| WhatsApp (simulado) | Simula el mensaje precargado | [whatsapp](wireframes/whatsapp.html) | UC-004 |

Inventario completo con enlaces de salida: [wireframes/INDEX.md](wireframes/INDEX.md).

## Diagramas por caso de uso

Índice: [diagrams/INDEX.md](diagrams/INDEX.md).

| UC | Flujo | Estados | Secuencia |
|---|---|---|---|
| 001 Entender qué vende | [flow](diagrams/uc-001-entender-que-vende/flow.md) | [states](diagrams/uc-001-entender-que-vende/states.md) | [sequence](diagrams/uc-001-entender-que-vende/sequence.md) |
| 002 Ver familia y medidas | [flow](diagrams/uc-002-ver-familia-y-medidas/flow.md) | [states](diagrams/uc-002-ver-familia-y-medidas/states.md) | [sequence](diagrams/uc-002-ver-familia-y-medidas/sequence.md) |
| 003 Catálogo PDF | [flow](diagrams/uc-003-acceder-al-catalogo/flow.md) | [states](diagrams/uc-003-acceder-al-catalogo/states.md) | [sequence](diagrams/uc-003-acceder-al-catalogo/sequence.md) |
| 004 Hablar con ventas | [flow](diagrams/uc-004-hablar-con-ventas/flow.md) | [states](diagrams/uc-004-hablar-con-ventas/states.md) | [sequence](diagrams/uc-004-hablar-con-ventas/sequence.md) |
| 005 Pedir cotización | [flow](diagrams/uc-005-pedir-cotizacion/flow.md) | [states](diagrams/uc-005-pedir-cotizacion/states.md) | [sequence](diagrams/uc-005-pedir-cotizacion/sequence.md) |
| 006 Llegar desde Google | [flow](diagrams/uc-006-llegar-desde-google/flow.md) | [states](diagrams/uc-006-llegar-desde-google/states.md) | [sequence](diagrams/uc-006-llegar-desde-google/sequence.md) |
| 007 Cómo llegar y horario | [flow](diagrams/uc-007-como-llegar-y-horario/flow.md) | [states](diagrams/uc-007-como-llegar-y-horario/states.md) | [sequence](diagrams/uc-007-como-llegar-y-horario/sequence.md) |
| 008 Servicios, calidad, FAQ | [flow](diagrams/uc-008-servicios-calidad-faq/flow.md) | [states](diagrams/uc-008-servicios-calidad-faq/states.md) | [sequence](diagrams/uc-008-servicios-calidad-faq/sequence.md) |

## Enlaces principales del prototipo

| Desde | Elemento | Hacia |
|---|---|---|
| Cualquier página | Logo | Inicio |
| Cualquier página | «↓ Catálogo» (cabecera) | Descarga de catálogo |
| Cualquier página | «Pedir cotización» (cabecera) | Contacto |
| Cualquier página | ☰ | Menú móvil |
| Cualquier página | «● WhatsApp» flotante | WhatsApp |
| Cualquier página | Teléfono de la barra superior | `tel:` |
| Inicio | Cada tarjeta de familia («Ver medidas →») | Ficha de familia |
| Inicio | «Ver productos y medidas» | Productos |
| Productos | Cada tarjeta | Ficha de familia |
| Ficha de familia | «Pedir cotización de chapas» | Contacto (`?rubro=chapas`) |
| Ficha de familia | Otras familias | Ficha de familia |
| Servicios, Calidad, FAQ, Ubicación, Productos, Ficha | Cierre «Pedir cotización» | Contacto |
| Ubicación | «Abrir en Google Maps» | Maps (enlace directo) |
| Contacto | «Enviar pedido» | Gracias |
| Contacto | «Ver política de privacidad» | Privacidad |
| Gracias | «Ver más productos» | Productos |

## Qué se mantiene y qué se corrige respecto del original

**Se mantiene:** las secciones del cliente con su nombre y orden (Productos, Servicios, Calidad,
FAQ, Ubicación, Contacto, Privacidad), la barra superior con dirección, teléfono y redes, la
cabecera fija con «Pedir cotización», el WhatsApp flotante y el orden de bloques de la Home
(portada, diferenciales, 01 Productos, 02 Servicios, 03 Calidad, 04 Contacto).

| # | Corrección de flujo | Objetivo | Origen |
|---|---|---|---|
| F1 | «Ver medidas →» lleva a la ficha de la familia, donde sí hay medidas | Saber qué hay | M3 |
| F2 | «Descargar catálogo» también en cabecera y portada, y funciona | Catálogo | M2 + **nueva** |
| F3 | WhatsApp con mensaje según la página | Ventas | M6 |
| F4 | Rubro preseleccionado en el formulario | Ventas | M5 |
| F5 | Adjuntar plano o despiece | Ventas | M4 |
| F6 | Página de gracias con próximo paso | Ventas | M8 |
| F7 | «Abierto ahora / Cerrado» | Ventas | M7 |
| F8 | Todas las páginas internas cierran con cotizar y WhatsApp, con mensaje precargado según la página | Ventas | Corrección: el original ya cierra con CTA en esas páginas; se unifica el cierre |

## Patrones de navegación

- **Cabecera fija** en todas las páginas: logo, «↓ Catálogo», «Pedir cotización» y menú ☰. Las tres
  acciones del cliente quedan siempre a un toque.
- **WhatsApp flotante** en todas las páginas de contenido. Se omite en el menú, en las pantallas simuladas y **en Contacto**
  (la página ya tiene su botón de WhatsApp y el flotante tapaba los campos del formulario en móvil).
- **Cierre de página** (F8): bloque oscuro con «Pedir cotización · {rubro}» y WhatsApp, con el
  mensaje precargado según la página.
- **Migas** (`Inicio / Productos / Chapas`) en páginas internas, que además alimentan `BreadcrumbList`.
- **Acordeón** para líneas, medidas y preguntas frecuentes: un solo bloque abierto a la vez.
- Botones y filas táctiles de **48 px de alto como mínimo**.

## Preguntas abiertas para la Fase 2

1. **Menú de escritorio:** el original lo tiene en una fila; el wireframe sólo cubre móvil. Se
   resuelve en la maqueta de escritorio de la Fase 2.
2. **«Pedir cotización · {rubro}» en el cierre de cada página:** ¿se mantiene el rótulo con rubro o
   queda sólo «Pedir cotización»? Rubro es más claro, pero lo decide el copy (C7).
3. **Tablas de medidas largas** (caños tiene 31 filas, ángulos 31 medidas): en móvil necesitan
   desplazamiento horizontal o un formato de lista. Se define en el sistema de diseño.
4. **Mapa embebido:** se carga tras consentimiento (ADR 0003). Falta definir el texto de la
   fachada.
5. **Actualizar `docs/01`:** correo (`hierrometalventas@hotmail.com`), enlace de Maps
   (`maps.app.goo.gl/XpbnH8AFw9iJSExs8`) e Instagram, según `docs/05` §7.
6. **Banner de cookies (D5 = sí):** aparece en la primera visita y no está dibujado en los wireframes. En móvil debe
   dejar libres la cabecera y el WhatsApp flotante. Se define en el sistema de diseño (texto en `docs/07` §3.10).
7. **Pantallas aún sin wireframe de escritorio** y sin alta fidelidad: son de la Fase 2.
