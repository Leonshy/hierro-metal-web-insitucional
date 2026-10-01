# Casos de uso — Web institucional Hierro Metal S.R.L.

> Fase 1 (UX). Entrada: `docs/01-inventario-contenido.md`, `docs/04-mejoras.md` y el sitio original
> del cliente. **Criterio rector (Leonshy):** el flujo se mantiene lo más cerca posible del sitio que
> propuso el cliente. Las correcciones son pequeñas y apuntan a tres objetivos del cliente:
>
> 1. **Saber rápido qué vende.**
> 2. **Llegar al catálogo.**
> 3. **Hablar con ventas sin fricción.**

Actores: **Visitante** (constructor, herrero, encargado de obra, mirando desde el celular), **Ventas**
(atiende WhatsApp, teléfono y la bandeja de cotizaciones), **Sistema** (sitio y panel).

---

## UC-001 — Entender qué vende Hierro Metal

| Campo | Detalle |
|---|---|
| Actores | Visitante |
| Precondiciones | Llega a `/` (directo, Instagram, WhatsApp o Google) |
| Flujo principal | 1. Ve portada con la propuesta y la ilustración de acero. 2. Lee la franja de diferenciales. 3. Recorre las 5 familias (bloque 01 — Productos). 4. Elige una familia → UC-002. |
| Alternativos | A. Quiere ir directo al catálogo → UC-003. B. Quiere hablar ya → UC-004. C. Quiere saber de taller o entrega → `/servicios`. |
| Postcondiciones | Sabe qué familias hay y tiene a un toque catálogo, WhatsApp y cotización |

## UC-002 — Ver una familia y sus medidas

| Campo | Detalle |
|---|---|
| Actores | Visitante, Sistema |
| Precondiciones | Eligió una familia en Home o `/productos`, o llegó por URL (UC-006) |
| Flujo principal | 1. Abre `/productos/{familia}`. 2. Ve la ilustración y la bajada. 3. Recorre las líneas (descripción y usos). 4. Abre las **medidas** de cada línea (tablas de `docs/05`). 5. Toca "Pedir cotización de {familia}" → UC-005 con rubro preseleccionado, o WhatsApp → UC-004. |
| Alternativos | A. La línea no tiene medidas en el catálogo: se muestra descripción y "Pedí medidas y precio". B. Su medida no está en la lista: aviso "¿Otra medida? Cortamos y fabricamos a medida". C. Quiere ver otra familia: navegación entre familias en la misma ficha. |
| Postcondiciones | Encontró su material y sus medidas sin salir a otra página |

## UC-003 — Acceder al catálogo PDF

| Campo | Detalle |
|---|---|
| Actores | Visitante, Sistema |
| Precondiciones | Cualquier página |
| Flujo principal | 1. Toca "Descargar catálogo" (cabecera, ficha de familia, Home y pie). 2. El Sistema entrega el PDF vigente desde URL fija. 3. Vuelve al sitio con la descarga hecha. |
| Alternativos | A. No hay PDF cargado en el panel: el botón se oculta (no se muestra un aviso roto). B. Móvil sin visor: se descarga el archivo. |
| Postcondiciones | Tiene el catálogo, y el sitio le mantiene a un toque la cotización y el WhatsApp |

## UC-004 — Hablar con ventas (WhatsApp o teléfono)

| Campo | Detalle |
|---|---|
| Actores | Visitante, Ventas |
| Precondiciones | Cualquier página, en celular o escritorio |
| Flujo principal | 1. Toca el botón flotante de WhatsApp (siempre visible) o el teléfono de la barra superior. 2. WhatsApp se abre con mensaje precargado según la página ("Hola, quiero cotizar chapas…"). 3. Ventas responde. |
| Alternativos | A. En escritorio sin WhatsApp: se abre WhatsApp Web, o copia el número. B. Prefiere llamar: `tel:` a un toque. C. Fuera de horario: se ve "Cerrado ahora" y el horario; igual puede escribir. |
| Postcondiciones | Ventas recibe la consulta con contexto de qué página la originó |

## UC-005 — Pedir cotización con el formulario

| Campo | Detalle |
|---|---|
| Actores | Visitante, Sistema, Ventas |
| Precondiciones | Entra a `/contacto` (por el botón "Pedir cotización" o desde una ficha con `?rubro=`) |
| Flujo principal | 1. Ve el formulario con el rubro ya elegido si vino de una familia. 2. Completa nombre, teléfono y detalle del pedido (empresa, correo y rubro son opcionales). 3. Opcionalmente adjunta plano o despiece (hasta 3 archivos). 4. Acepta el consentimiento y toca "Enviar pedido". 5. El Sistema guarda el pedido, avisa por correo y lo muestra en la bandeja. 6. Llega a `/contacto/gracias` con el próximo paso. |
| Alternativos | A. Falta un dato obligatorio: error junto al campo, sin perder lo escrito. B. Adjunto con formato o tamaño no permitido: aviso claro. C. Falla el correo: el pedido queda guardado igual (regla 8). D. Spam (honeypot o límite por IP): se descarta sin mostrar error técnico. E. Prefiere no escribir: botón de WhatsApp al lado del formulario → UC-004. |
| Postcondiciones | Cotización guardada y Ventas notificada; el visitante sabe que se recibió y qué sigue |

## UC-006 — Llegar desde Google a un producto y cotizar

| Campo | Detalle |
|---|---|
| Actores | Visitante |
| Precondiciones | Busca algo como "chapa antideslizante Fernando de la Mora" |
| Flujo principal | 1. Entra directo a `/productos/{familia}`. 2. Confirma que tienen su material (UC-002). 3. Cotiza (UC-005) o escribe (UC-004). |
| Alternativos | A. Quiere ver todo lo demás: "Todos los productos" y la cabecera siguen disponibles. |
| Postcondiciones | Entra por la ficha, no por la home, y llega a cotizar sin pasos intermedios |

## UC-007 — Saber cómo llegar y cuándo está abierto

| Campo | Detalle |
|---|---|
| Actores | Visitante |
| Precondiciones | Quiere ir al depósito |
| Flujo principal | 1. Abre `/ubicacion` (cabecera, pie o barra superior). 2. Ve dirección, horarios y "Abierto ahora / Cerrado". 3. Toca para cargar el mapa (fachada de consentimiento) o "Abrir en Google Maps". |
| Alternativos | A. No acepta cargar el mapa: usa el enlace directo a Maps. B. Quiere avisar que va: WhatsApp → UC-004. |
| Postcondiciones | Sabe cómo llegar y a qué hora |

## UC-008 — Conocer servicios, calidad y resolver dudas

| Campo | Detalle |
|---|---|
| Actores | Visitante |
| Precondiciones | Quiere saber si cortan, doblan, galvanizan o entregan en obra, o si hay certificados |
| Flujo principal | 1. Abre `/servicios`, `/calidad` o `/preguntas-frecuentes`. 2. Lee el servicio o abre la pregunta. 3. Cada página termina con "Pedir cotización" y WhatsApp → UC-005 / UC-004, con el rubro correspondiente. |
| Alternativos | A. Leyó la política de privacidad desde el pie → `/privacidad`. |
| Postcondiciones | Despejó la duda y llegó a la cotización con el rubro de servicio elegido |

---

## Qué se mantiene del original y qué se corrige (flujo)

**Se mantiene:** las 8 secciones del cliente con su orden y nombres (Productos, Servicios, Calidad,
FAQ, Ubicación, Contacto, Privacidad), la barra superior con datos de contacto, la cabecera fija con
el botón "Pedir cotización", el botón flotante de WhatsApp, y el orden de la Home.

**Correcciones pequeñas** (todas ya listadas en `docs/04`, salvo las marcadas **nueva**):

| # | Corrección | Objetivo | Origen |
|---|---|---|---|
| F1 | "Ver medidas →" lleva a la ficha de la familia, donde sí hay medidas | Saber qué hay | M3 |
| F2 | "Descargar catálogo" se ve en la **cabecera** además del pie, y funciona | Catálogo | M2 + **nueva** (el original lo tiene sólo en pie y contacto) |
| F3 | WhatsApp con mensaje según página | Ventas | M6 |
| F4 | Rubro preseleccionado en el formulario | Ventas | M5 |
| F5 | Adjuntar plano en el formulario | Ventas | M4 |
| F6 | Página de gracias con próximo paso | Ventas | M8 |
| F7 | "Abierto ahora / Cerrado" | Ventas | M7 |
| F8 | Todas las páginas internas cierran con cotizar **y** WhatsApp, con el mensaje precargado según la página | Ventas | El original ya cierra con CTA en Productos, Servicios, Calidad, FAQ y Ubicación; se unifica y se agrega el WhatsApp con contexto |

## Pendientes para aprobar antes de la Fase 2

1. **Enlace de Maps:** el inventario (`docs/01`) tiene `maps.app.goo.gl/aV2wRxNf9gknmhEz6` y el catálogo
   lleva a `maps.app.goo.gl/XpbnH8AFw9iJSExs8`. Se usa el segundo (decisión del 01/10/2026).
   Hay que actualizar `docs/01`.
2. Correo y vendedores: el flujo no los muestra (decisiones 9 y 10 de `docs/05`); `docs/01`
   todavía dice `admin@hierrometal.com`.
