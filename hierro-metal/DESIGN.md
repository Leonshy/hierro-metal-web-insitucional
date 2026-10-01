---
name: Hierro Metal S.R.L.
description: Sistema visual del sitio institucional y de cotización de Hierro Metal. Amarillo y negro industriales, títulos condensados, rótulos de stock.
colors:
  marca: "#f9ed31"
  marca-hover: "#f3de30"
  marca-suave: "#dfd57d"
  marca-claro: "#fdf8b8"
  tinta: "#231f20"
  tinta-hover: "#000000"
  texto: "#4a4444"
  texto-sobre-marca: "#3d3838"
  gris: "#706c64"
  linea: "#a3a5a8"
  alterno: "#f9f9f9"
  panel: "#efeeea"
  blanco: "#ffffff"
  claro: "#cfccc6"
  claro-2: "#b3afa9"
  claro-3: "#e4e1dc"
  barra: "#d9d6d0"
  pie-legal: "#8b8681"
  enlace: "#8a6d00"
  error: "#b00020"
  error-suave: "#fbeaed"
  exito: "#1e6b3a"
  exito-suave: "#eaf4ed"
typography:
  display:
    fontFamily: "'Barlow Condensed', 'Arial Narrow', Arial, sans-serif"
    fontSize: "clamp(42px, 9.5vw, 88px)"
    fontWeight: 700
    lineHeight: 0.95
    letterSpacing: "-0.005em"
  headline:
    fontFamily: "'Barlow Condensed', 'Arial Narrow', Arial, sans-serif"
    fontSize: "clamp(34px, 7vw, 62px)"
    fontWeight: 700
    lineHeight: 0.95
  title:
    fontFamily: "'Barlow Condensed', 'Arial Narrow', Arial, sans-serif"
    fontSize: "29px"
    fontWeight: 700
    lineHeight: 1.02
  title-linea:
    fontFamily: "'Barlow Condensed', 'Arial Narrow', Arial, sans-serif"
    fontSize: "25px"
    fontWeight: 700
    lineHeight: 1.05
  title-paso:
    fontFamily: "'Barlow Condensed', 'Arial Narrow', Arial, sans-serif"
    fontSize: "24px"
    fontWeight: 700
    lineHeight: 0.95
  title-diferencial:
    fontFamily: "'Barlow Condensed', 'Arial Narrow', Arial, sans-serif"
    fontSize: "26px"
    fontWeight: 700
    lineHeight: 0.95
  title-servicio:
    fontFamily: "'Barlow Condensed', 'Arial Narrow', Arial, sans-serif"
    fontSize: "27px"
    fontWeight: 700
    lineHeight: 0.95
  title-dato:
    fontFamily: "'Barlow Condensed', 'Arial Narrow', Arial, sans-serif"
    fontSize: "30px"
    fontWeight: 700
    lineHeight: 0.95
  title-prosa:
    fontFamily: "'Barlow Condensed', 'Arial Narrow', Arial, sans-serif"
    fontSize: "clamp(26px, 4.6vw, 38px)"
    fontWeight: 700
    lineHeight: 0.95
  button:
    fontFamily: "'Barlow Condensed', 'Arial Narrow', Arial, sans-serif"
    fontSize: "19px"
    fontWeight: 600
    letterSpacing: "0.09em"
  nav:
    fontFamily: "'IBM Plex Sans', system-ui, sans-serif"
    fontSize: "13px"
    fontWeight: 600
    letterSpacing: "0.09em"
  body-small:
    fontFamily: "'IBM Plex Sans', system-ui, sans-serif"
    fontSize: "14.5px"
    fontWeight: 400
    lineHeight: 1.6
  body-ui:
    fontFamily: "'IBM Plex Sans', system-ui, sans-serif"
    fontSize: "15px"
    fontWeight: 400
    lineHeight: 1.6
  caption:
    fontFamily: "'IBM Plex Sans', system-ui, sans-serif"
    fontSize: "13px"
    fontWeight: 400
    lineHeight: 1.5
  label-small:
    fontFamily: "'IBM Plex Mono', ui-monospace, monospace"
    fontSize: "12px"
    fontWeight: 500
    letterSpacing: "0.06em"
  body:
    fontFamily: "'IBM Plex Sans', system-ui, sans-serif"
    fontSize: "16.5px"
    fontWeight: 400
    lineHeight: 1.65
  label:
    fontFamily: "'IBM Plex Mono', ui-monospace, monospace"
    fontSize: "11px"
    fontWeight: 500
    letterSpacing: "0.18em"
rounded:
  none: "0"
spacing:
  margen: "20px"
  seccion: "60px"
  seccion-chica: "44px"
  tactil: "48px"
components:
  button-amarillo:
    backgroundColor: "{colors.marca}"
    textColor: "{colors.tinta}"
    rounded: "{rounded.none}"
    padding: "0 24px"
    height: "52px"
  button-amarillo-hover:
    backgroundColor: "{colors.marca-hover}"
  button-negro:
    backgroundColor: "{colors.tinta}"
    textColor: "{colors.blanco}"
    rounded: "{rounded.none}"
    padding: "0 24px"
    height: "52px"
  button-linea:
    backgroundColor: "{colors.blanco}"
    textColor: "{colors.tinta}"
    rounded: "{rounded.none}"
    padding: "0 24px"
    height: "52px"
  campo:
    backgroundColor: "{colors.blanco}"
    textColor: "{colors.tinta}"
    rounded: "{rounded.none}"
    padding: "13px 14px"
    height: "48px"
  campo-error:
    backgroundColor: "{colors.error-suave}"
    textColor: "{colors.error}"
  ficha:
    backgroundColor: "{colors.blanco}"
    textColor: "{colors.tinta}"
    rounded: "{rounded.none}"
    padding: "20px 20px 24px"
  ficha-destacada:
    backgroundColor: "{colors.tinta}"
    textColor: "{colors.blanco}"
    rounded: "{rounded.none}"
    padding: "30px 22px"
  insignia:
    backgroundColor: "{colors.marca}"
    textColor: "{colors.tinta}"
    padding: "7px 12px"
---

# Design System: Hierro Metal S.R.L.

## Overview

**Creative North Star: "La etiqueta de stock"**

El sitio se lee como un depósito bien ordenado en el que cada cosa tiene su etiqueta: un rótulo en monoespaciada, un índice (`01 · 7 líneas`), un título condensado en mayúsculas y una acción. Planos de color llenos (blanco, negro, amarillo) se alternan para dar ritmo a cada página, y las retículas de 1 px ordenan el contenido como estantes. Nada se esconde detrás de efectos: no hay radios, no hay degradados, no hay sombras decorativas.

La estética **es la que el cliente ya diseñó** en su sitio de referencia y se conserva. Lo que este sistema agrega es lo que el original no definía: estados completos (hover, foco, error, cargando, éxito, deshabilitado), tablas de medidas, el banner de cookies y los contrastes corregidos para WCAG 2.1 AA. Mejorar no es cambiar de estética.

Tono: directo y ordenado. El visitante es alguien con una obra, en el celular, que quiere ver el material y mandar su lista.

**Key Characteristics:**
- Amarillo institucional sobre negro; el amarillo nunca lleva texto blanco.
- Títulos en Barlow Condensed en mayúsculas, interlineado .95.
- Rótulos en IBM Plex Mono, 11 px, mayúsculas, tracking .18em, como etiquetas de stock.
- Retículas de 1 px dibujadas con `box-shadow`, no con el fondo del contenedor.
- Botones rectos con borde inferior de 3 px.
- Sin radios, sin degradados, una sola sombra en todo el sistema.

## Colors

Una paleta corta y de alto contraste: dos colores de marca (amarillo y negro), una escala de grises cálidos y los colores de estado.

### Primary
- **Amarillo institucional** (#f9ed31): fondo de los botones principales, de la insignia, de las franjas de clima amarillo y del foco por teclado. Hover: **Amarillo hover** (#f3de30). Sobre negro, para notas: **Amarillo suave** (#dfd57d). Borde claro del degradado del logo: **#fdf8b8**.
- **Negro institucional** (#231f20): texto principal, fondo de las secciones oscuras, de la barra superior y del pie.

### Neutral
- **Texto de cuerpo** (#4a4444): párrafos sobre blanco (9.5:1). **Texto sobre amarillo** (#3d3838): bajadas sobre el amarillo (9.4:1).
- **Gris de rótulos** (#706c64): rótulos, etiquetas y ayudas sobre blanco (5.2:1) y sobre el panel (4.5:1). Era #837f76 en el original (3.99:1, no pasaba AA).
- **Línea** (#a3a5a8): retículas y bordes. Es decorativo (2.5:1): nunca lleva texto.
- **Alterno** (#f9f9f9): hover de fichas, pregunta abierta, aviso. **Panel** (#efeeea): fondo de las ilustraciones.
- **Claro** (#cfccc6) y **Claro 2** (#b3afa9): bajadas y textos secundarios sobre negro (10.2:1 y 7.5:1).
- **Enlace** (#8a6d00): enlaces en prosa (4.9:1).
- **Pie y barra sobre negro:** enlaces del pie **#e4e1dc**, dirección de la barra superior **#d9d6d0** y línea legal **#8b8681** (4.5:1). **Negro de hover** (#000000) para el botón negro.

### Ilustraciones
- Las seis ilustraciones SVG de secciones de acero del cliente usan sus propios grises (**acero claro** #c9c6c0, y cinco tonos oscuros para la portada: #3d3939, #4a4644, #5a5552, #2b2728 y #1c1819). Son tokens (`--color-acero-*`), no valores sueltos.

### Estado
- **Error** (#b00020) con fondo **#fbeaed**. **Éxito** (#1e6b3a) con fondo **#eaf4ed**. Ambos pares pasan AA.

### Named Rules
**The Yellow-Is-Never-White Rule.** Sobre el amarillo sólo van tinta (13.3:1) o texto sobre amarillo. Nunca texto blanco.

**The No-Gray-On-Black Rule.** El gris de rótulos no se usa sobre negro (3.1:1). Sobre negro: claro y claro 2.

**The Three Climates Rule.** Cada sección es clara (blanca), oscura (negra) o amarilla, y se alternan. No hay un cuarto fondo de sección.

## Typography

**Display Font:** Barlow Condensed (con Arial Narrow, Arial)
**Body Font:** IBM Plex Sans (con system-ui)
**Label/Mono Font:** IBM Plex Mono (con ui-monospace)

**Character:** una condensada pesada y seca para titular, como letra de depósito, junto a una sans neutra para leer y una mono para etiquetar. Las tres con licencia SIL OFL, autohospedadas.

### Hierarchy
- **Display** (700, clamp(42px, 9.5vw, 88px), 0.95, mayúsculas): título de portada.
- **Headline** (700, clamp(34px, 7vw, 62px), 0.95, mayúsculas): título de sección. Título de página interna: clamp(38px, 8vw, 74px). Título de familia: clamp(30px, 5.6vw, 50px).
- **Title** (700, 29px, 1.02, mayúsculas): título de ficha. Línea: 25px. Paso: 24px. Pregunta frecuente: 25px (600).
- **Body** (400, 16.5px, 1.65, máx. 62 ch): bajadas. Cuerpo de ficha: 14.5px / 1.6. Prosa: 16.5px / 1.7, máx. 68 ch.
- **Label** (500, 11px, tracking .18em, mayúsculas): rótulos, índices, usos y horarios.
- **Botón** (Barlow Condensed 600, 19px, tracking .09em, mayúsculas). **Navegación** (IBM Plex Sans 600, 13px, tracking .09em, mayúsculas).
- **Títulos menores** (Barlow Condensed 700): servicio 27px, diferencial 26px, dato de contacto 30px, título de prosa clamp(26px, 4.6vw, 38px).
- **Apoyo:** cuerpo chico 14.5px, texto de interfaz 15px, ayudas y notas 13px, etiquetas chicas en mono 12px.

### Named Rules
**The Stock Label Rule.** Todo lo que clasifica (índice, rubro, uso, estado de horario) va en mono, 11 px, mayúsculas. Es la voz de la etiqueta de stock y no se usa para texto corrido.

**The Numerals Rule.** Las medidas se escriben en la condensada (primera columna de las tablas) o en mono con cifras tabulares (resto), nunca en la sans de cuerpo.

## Layout

Ancho máximo de 1300 px con margen lateral de 20 px. Secciones de 60/70 px; secciones chicas de 44/48 px; encabezado de página de 46/44 px. Grillas `repeat(auto-fit, minmax(…, 1fr))`: 280 px para fichas, 260 para líneas, 250 para pasos, 230 para diferenciales.

Las retículas dibujan sus líneas de 1 px con `box-shadow: 0 0 0 1px`, no con el fondo del contenedor, para que las celdas que sobran en la última fila queden vacías y no se vean como bloques grises.

Breakpoints: 520 px (logo y botones chicos), 860 px (se oculta la dirección de la barra y aparece «↓ Catálogo»), 1040 px (navegación de escritorio). **Mobile-first**: botones y filas táctiles de 48 px de alto como mínimo; teléfono y WhatsApp a un toque.

## Elevation & Depth

Sistema plano. La profundidad se logra por planos de color y por retículas, no por sombras. La **única** sombra es la del botón flotante de WhatsApp.

### Shadow Vocabulary
- **Flotante** (`box-shadow: 0 10px 28px rgba(35, 31, 32, .32)`): sólo el botón de WhatsApp.

### Named Rules
**The Flat-By-Default Rule.** Ninguna ficha, botón, campo o aviso lleva sombra. El estado (hover, foco) se comunica con color, no con elevación.

## Shapes

Esquinas rectas en todo (`border-radius: 0`). Bordes de 1 px en `--color-linea`. Los botones amarillos llevan un borde inferior de 3 px en negro (blanco sobre fondo negro); es la firma del sistema. El foco por teclado es un contorno de 3 px con 2 px de separación: **negro** sobre fondos claros y amarillos, **amarillo** sobre fondos negros (el amarillo sobre blanco daba 1,2:1 y no pasaba AA).

## Components

### Buttons
- **Shape:** rectos, sin radio, alto de 52 px, texto en condensada 19 px.
- **Amarillo (principal):** fondo #f9ed31, texto tinta, borde inferior de 3 px. Hover #f3de30. Sobre negro, borde inferior blanco.
- **Negro:** fondo tinta, texto blanco. **Línea:** borde de 1 px; sobre negro, borde blanco al 40 % que pasa a amarillo en hover. **Línea amarilla:** sólo sobre negro.
- **Estados:** hover por color; foco con contorno tinta (amarillo sobre negro); activo baja 1 px; deshabilitado al 50 %; cargando con un anillo giratorio dentro del botón.

### Cards / Containers (Ficha)
- **Corner Style:** recto. **Background:** blanco; destacada en negro.
- **Border:** retícula de 1 px (`box-shadow`). **Internal Padding:** 20 px.
- **Contenido:** ilustración sobre panel, índice mono, título condensado, texto, y un «Ver medidas →» como etiqueta amarilla.

### Medidas (tabla y línea desplegable)
- **Línea:** `<details>` con nombre en condensada, conteo en mono y chevron que gira. Abierta, fondo alterno.
- **Tabla:** borde de 1 px, encabezado en mono sobre panel, primera columna en condensada, cifras en mono tabulares. En móvil se desplaza horizontalmente y muestra el borde que sigue.

### Inputs / Fields
- **Style:** borde de 1 px en línea, fondo blanco, sin radio, alto de 48 px, rótulo mono arriba.
- **Focus:** borde tinta y contorno tinta de 3 px. **Borde en reposo:** gris de rótulos (5,2:1), no la línea decorativa.
- **Error:** borde y texto en error, fondo suave, mensaje junto al campo. **Disabled:** fondo alterno, texto gris.
- **Adjuntos:** zona con borde punteado y lista de archivos con su estado.

### Aviso
Marco completo de 1 px con una pestaña llena arriba que dice el tipo (Aviso, Error, Listo). No lleva borde lateral grueso. Tres tipos: info (alterno), error, éxito.

### Navigation
- **Cabecera:** fija, blanco al 96 %, línea inferior. Logo, navegación (IBM Plex Sans 600, 13 px, mayúsculas, subrayado amarillo de 3 px en la página actual), «↓ Catálogo», «Pedir cotización» y menú.
- **Móvil:** menú de 6 enlaces en condensada 24 px. Debajo de 1040 px, cabecera con botón amarillo y menú.

### Banner de cookies
Barra negra con borde superior amarillo. «Rechazar todo», «Configurar» y «Aceptar todo» pesan lo mismo. El WhatsApp flotante sube mientras está visible.

### WhatsApp flotante
Botón amarillo con borde inferior negro y la única sombra del sistema, abajo a la derecha, siempre visible.

## Do's and Don'ts

### Do:
- **Do** usar sólo tokens: ningún color, tipografía ni medida de marca literal en un componente.
- **Do** alternar los tres climas de sección (blanco, negro, amarillo) para dar ritmo.
- **Do** escribir los rótulos en mono, 11 px, mayúsculas, con tracking .18em.
- **Do** dibujar las retículas con `box-shadow: 0 0 0 1px`.
- **Do** mantener 48 px de alto táctil y respetar `prefers-reduced-motion`.
- **Do** terminar cada página con una acción: cotizar o escribir por WhatsApp.

### Don't:
- **Don't** usar radios, degradados ni sombras (salvo la del botón de WhatsApp).
- **Don't** poner texto blanco sobre amarillo, ni gris de rótulos sobre negro.
- **Don't** reemplazar las ilustraciones SVG de secciones de acero por fotos de stock o iconos genéricos.
- **Don't** usar bordes laterales gruesos en avisos o tarjetas.
- **Don't** inventar pruebas: no hay fotos propias, testimonios ni clientes citables.
- **Don't** cargar Google Fonts en el sitio real: las fuentes se autohospedan.
