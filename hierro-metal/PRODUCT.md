# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Stack

Laravel 13, PHP 8.3+, MySQL 8, Blade con Livewire y Alpine.js, Tailwind CSS v4 con tokens en `@theme`, Vite. Panel de administración reutilizado de Dante (Filament 5). Despliegue en Plesk de webparaguay. Decidido por webparaguay (ver `CLAUDE.md`).

## Users

Quien compra materiales metálicos para una obra o un taller, casi siempre desde el celular y en el lugar de trabajo:

- Constructores, herreros, encargados de obra e industrias que compran por mayor.
- Arquitectos e ingenieros que piden medidas exactas y certificados.
- Compras de empresas que piden una cotización formal, a veces por correo y con varias líneas.
- Particulares que necesitan una cantidad chica.

El trabajo del visitante: encontrar su material, confirmar que Hierro Metal lo tiene y lo corta, y mandar su lista de materiales o su plano en un minuto, o hablar con ventas por WhatsApp o teléfono.

## Product Purpose

Sitio institucional y de catálogo de Hierro Metal S.R.L. (importación y venta mayorista y minorista de chapas, perfiles, tubos, varillas y accesorios de acero, con servicios de taller y entrega en obra; casa matriz en Fernando de la Mora, Central, Paraguay). **El objetivo de negocio es uno: generar pedidos de cotización.** Todo lo demás (catálogo, calidad, FAQ, ubicación) existe para que el visitante llegue al formulario o a WhatsApp con confianza y con el pedido bien armado.

Éxito: cotizaciones recibidas (llegadas a `/contacto/gracias`) y clics en WhatsApp, más aparecer en Google para búsquedas de producto locales.

## Positioning

Importación directa (mayor y menor) + taller propio (corte, plegado, perforado, perfiles C y U, galvanización) + flota propia que entrega en obra, todo en el mismo depósito de Fernando de la Mora. Un proveedor que sólo revende no puede decir "te lo dejamos cortado y en tu obra".

## Operating Context

- El sitio **no vende en línea**: no hay carrito, precios ni stock publicados. El precio y la disponibilidad se confirman por cotización, en el día.
- Las cotizaciones llegan por formulario (con adjuntos de plano o despiece), WhatsApp o teléfono y las atiende Ventas desde una bandeja en el panel.
- Horario: lunes a viernes 07:00–17:00, sábados 07:00–12:00. Ventas se centraliza en un único número corporativo (+595 981 320 675).
- Catálogo de materiales 2026 en PDF, con medidas por línea (transcripto en `docs/05`).
- El cliente ya armó su propio sitio de referencia (`referencia/sitio-cliente-original.html`): es la fuente de contenido y de aspecto gráfico.

## Capabilities and Constraints

- Sólo español, con voseo.
- Rutas reales por página, formulario de cotización que guarda el pedido antes de enviar el correo, adjuntos con lista blanca (pdf, jpg, jpeg, png, webp, dwg, dxf, xlsx; 10 MB, 3 archivos), anti-spam en capas.
- Google Maps se carga sólo con clic de consentimiento. GA4 y Meta se cargan sólo con consentimiento de cookies (decisión D5 = sí; pendiente de revisión legal y de las cuentas).
- Fuentes autohospedadas (licencia OFL).
- Sin datos personales reales en desarrollo ni en pruebas.
- La política de privacidad no se publica sin revisión del asesor legal del cliente.
- Indefinido: logo en vector (D4), fotos propias (D6), correo definitivo de cotizaciones.

## Brand Commitments

Obligatorios, definidos por el cliente en su sitio y confirmados como regla del proyecto (`CLAUDE.md`, reglas 1 a 4):

- El contenido del cliente es la fuente de verdad: ningún texto se inventa y se conserva el voseo.
- Aspecto gráfico del cliente: amarillo institucional sobre negro, títulos en Barlow Condensed en mayúsculas, rótulos en IBM Plex Mono, retículas de 1 px, botones rectos con borde inferior de 3 px, y las seis ilustraciones SVG de secciones de acero. Mejorar no es cambiar de estética.
- Nombre: Hierro Metal S.R.L.

## Evidence on Hand

- Catálogo de materiales 2026 (PDF) y su transcripción en `docs/05-medidas-catalogo.md`.
- Certificado de calidad del fabricante en las chapas (afirmación del cliente).
- Las seis ilustraciones SVG en `referencia/assets/` y un logo en JPEG de 225 px.
- **No hay:** fotos propias, testimonios, clientes citables, premios ni precios publicables. Nada de eso se inventa.

## Product Principles

1. Cada página termina en una acción: cotizar o escribir por WhatsApp.
2. Primero lo que hay: el visitante tiene que ver qué materiales y qué medidas se ofrecen sin dar vueltas.
3. Un pedido perdido es plata perdida: el formulario nunca pierde lo escrito ni el pedido.
4. Fiel al cliente: se mejora lo que traba la cotización; no se cambia su voz ni su estética.
5. Pensado para el celular en obra: botones grandes, teléfono y WhatsApp siempre a un toque, carga rápida.

## Accessibility & Inclusion

WCAG 2.1 AA: contraste de cada par de tokens, foco visible, navegación por teclado, lector de pantalla en el formulario, `prefers-reduced-motion` respetado. El amarillo institucional nunca lleva texto blanco.
