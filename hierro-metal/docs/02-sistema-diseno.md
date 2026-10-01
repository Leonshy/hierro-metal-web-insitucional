# 02 — Sistema de diseño

> Extraído de la hoja de estilos de `referencia/sitio-cliente-original.html`.
> Se conserva la estética; se corrige lo que no pasa accesibilidad y se completan los estados que
> el original no define. Se carga en `impeccable` con `/impeccable init` en la Fase 2.
> **Ningún componente usa valores literales: sólo estos tokens.**
> Implementación de la Fase 2 en `diseno/` y documento visual en `DESIGN.md` (versión vigente si difieren).

---

## 1. Carácter

Industrial, directo, de depósito. Planos de color llenos (amarillo, negro, blanco), retículas de
línea fina, títulos condensados en mayúsculas, rótulos en monoespaciada como si fueran
etiquetas de stock. **Sin radios, sin sombras decorativas, sin degradados.** La única sombra del
original es la del botón flotante de WhatsApp.

Tres "climas" de sección que se alternan y le dan ritmo a cada página:

| Clima | Fondo | Uso |
|---|---|---|
| Clara | blanco | contenido, fichas, formularios |
| Oscura (`.oscura`) | negro institucional | servicios, cierres de página, portada |
| Amarilla (`.amarilla`) | amarillo institucional | diferenciales, llamados a calidad, CTA de cierre |

## 2. Tokens de color

| Token | Original | Nuevo | Uso | Nota |
|---|---|---|---|---|
| `--color-marca` | `#f9ed31` | igual | amarillo institucional | Del logo |
| `--color-marca-hover` | `#f3de30` | igual | hover de botones amarillos | |
| `--color-marca-suave` | `#dfd57d` | igual | nota de portada sobre negro | 10.8:1 sobre negro ✔ |
| `--color-tinta` | `#231f20` | igual | negro institucional, texto principal | |
| `--color-texto` | `#4a4444` | igual | texto de cuerpo | 9.5:1 sobre blanco ✔ |
| `--color-gris` | `#837f76` | **`#706c64`** | rótulos, etiquetas, ayudas | ⚠️ El original da **3.99:1** sobre blanco: no pasa AA en 11–13 px. El nuevo da 5.2:1 sobre blanco y 4.5:1 sobre `--color-panel` |
| `--color-linea` | `#a3a5a8` | igual | retículas de 1 px, bordes | Decorativo, no lleva texto |
| `--color-alterno` | `#f9f9f9` | igual | hover de fichas, FAQ abierto, aviso | |
| `--color-panel` | `#efeeea` | igual | fondo de ilustraciones de ficha | |
| `--color-claro` | `#cfccc6` | igual | bajadas sobre negro | 10.2:1 ✔ |
| `--color-claro-2` | `#b3afa9` | igual | textos secundarios sobre negro | 7.5:1 ✔ |
| `--color-enlace` | `#8a6d00` | igual | enlaces en prosa | 4.9:1 ✔ |
| `--color-error` | `#b00020` | igual | requerido, errores | 7.3:1 ✔ |
| `--color-exito` | — | **`#1e6b3a`** (fondo `#eaf4ed`) | confirmación del formulario | 6.5:1 sobre blanco · 5.8:1 sobre su fondo. Definido en `diseno/tokens.css` |

**Pares verificados:** tinta/amarillo 13.3:1 · texto/amarillo 7.8:1 · amarillo/tinta 13.3:1.
El amarillo **nunca** lleva texto blanco.

## 3. Tipografía

| Token | Familia | Pesos | Uso |
|---|---|---|---|
| `--font-condensada` | Barlow Condensed | 500, 600, 700 | títulos, botones, títulos de ficha, preguntas de FAQ |
| `--font-texto` | IBM Plex Sans | 400, 600 | cuerpo, navegación, formularios |
| `--font-mono` | IBM Plex Mono | 500 | rótulos, índices, etiquetas, usos, horarios |

Las tres con licencia **SIL Open Font**: costo cero, autohospedadas (CLAUDE.md regla 15).
Fallbacks del original: `'Arial Narrow', Arial, sans-serif` / `system-ui…` / `ui-monospace…`.

### Escala (del original)

| Rol | Tamaño |
|---|---|
| H1 portada | `clamp(42px, 9.5vw, 88px)`, mayúsculas, interlineado .95 |
| H1 interna | `clamp(38px, 8vw, 74px)` |
| Título de sección | `clamp(34px, 7vw, 62px)` |
| Título de familia | `clamp(30px, 5.6vw, 50px)` |
| Título de ficha | 29 px · línea 25 px · paso 24 px · FAQ 25 px |
| Bajada | 16.5–17 px, interlineado 1.65, máx. 62 ch |
| Cuerpo de ficha | 14.5 px, interlineado 1.6 |
| Rótulo | 11 px mono, tracking .18em, mayúsculas |
| Botón | 19 px condensada 600, tracking .09em, mayúsculas |

## 4. Espaciado y estructura

- Ancho máximo `--ancho: 1300px`, margen lateral 20 px.
- Sección: 60/70 px · sección chica: 44/48 px · encabezado de página: 46/44 px.
- **Retículas de 1 px con `box-shadow: 0 0 0 1px`** (no con fondo del contenedor), para que las
  celdas vacías de la última fila no se vean como bloques grises. Conservar la técnica.
- Grillas `repeat(auto-fit, minmax(…, 1fr))`: 280 px fichas, 260 px líneas, 250 px pasos,
  230 px diferenciales.
- Breakpoints del original: 520 px (logo y CTA chicos), 860 px (se oculta dirección en barra),
  1040 px (navegación de escritorio).

## 5. Componentes

| Componente | Clase original | Componente Blade | Notas para el nuevo |
|---|---|---|---|
| Botón amarillo | `.btn.btn-amarillo` | `<x-boton variante="amarillo">` | Borde inferior 3 px tinta; sobre negro, borde blanco |
| Botón negro | `.btn-negro` | `variante="negro"` | |
| Botón línea | `.btn-linea` | `variante="linea"` | Se invierte en clima oscuro |
| Botón línea amarilla | `.btn-linea-amarilla` | `variante="linea-amarilla"` | Sólo en clima oscuro |
| Rótulo | `.rotulo` | `<x-rotulo>` | Color cambia por clima |
| Insignia | `.insignia` | `<x-insignia>` | |
| Ficha de familia | `.rejilla .ficha` | `<x-ficha-familia>` | Ilustración + índice + título + texto + "Ver medidas →" |
| Ficha destacada | `.ficha-destacada` | `<x-ficha-catalogo>` | Negra, con botón de descarga |
| Franja de diferenciales | `.diferenciales` | `<x-diferenciales>` | |
| Rejilla oscura | `.rejilla-oscura` | `<x-rejilla-oscura>` | |
| Lista de líneas | `.lineas` | `<x-linea>` | Título + texto + usos (mono). **Nuevo:** tabla de medidas desplegable si hay (D2) |
| Índice de anclas | `.indice` | `<x-indice>` | En el nuevo enlaza a `/productos/{familia}` |
| Pasos | `.pasos` | `<x-pasos>` | Contador `01`… en recuadro amarillo |
| Compromisos | `.compromisos` | `<x-compromiso>` | |
| Prosa | `.prosa` | `<x-prosa>` | Para calidad y privacidad |
| FAQ | `.faq details` | `<x-faq>` | `<details>` nativo + animación de altura (emil) |
| Datos de contacto | `.datos` | `<x-dato-contacto>` | Variante `grande` para el teléfono |
| Horarios | `.horarios` | `<x-horarios>` | Desde el módulo Horarios. **Nuevo:** "Abierto ahora / Cerrado" según hora de Paraguay |
| Campo | `.campo` | `<x-campo>` | Label mono, input sin radio, foco amarillo 3 px |
| Aviso | `.aviso` | `<x-aviso tipo="info|error|exito">` | Borde izquierdo 4 px |
| WhatsApp flotante | `.whatsapp-flotante` | `<x-whatsapp-flotante>` | Mensaje precargado por contexto (M6) |
| Barra de datos | `.barra-datos` | parcial de layout | |
| Cabecera | `.cabecera` | parcial de layout | Sticky, blanco 96 % + blur |
| Pie | `.pie` | parcial de layout | |
| Mapa | `.mapa` | `<x-mapa-consentido>` | Fachada con la ilustración rayada del original (ADR 0003) |

**Se descarta del original:** `.barra-revision`, `.aviso-flotante`, `.mapa-sustituto` como
sustituto permanente y el enrutador por hash — eran andamiaje de la vista previa.

## 6. Ilustraciones

Seis SVG en `referencia/assets/`: `portada` (1600×700, secciones de acero sobre piso amarillo,
fondo rayado) y una por familia (320×180, fondo `--color-panel`). Para el nuevo:

- Pasar los colores literales a `currentColor` / variables (`--color-marca`, `--color-tinta`,
  `--color-linea`) para cumplir la regla 11.
- Optimizar con SVGO y servir inline (son livianas y no bloquean).
- Portada: animación de entrada escalonada de cada sección de acero, 600–900 ms en total,
  desactivada con `prefers-reduced-motion`.
- Si el cliente entrega fotos propias, conviven: la ilustración queda como identidad gráfica, la
  foto como prueba. No se reemplaza una por otra sin pasar por el Hito 2.

## 7. Logo

El original es un **JPEG de 225×225 px** (`referencia/assets/logo-original-225px.jpg`), mostrado a 84 px de
alto: se ve borroso en pantallas de alta densidad y tiene fondo blanco rectangular.

**Redibujo (D4, octubre 2026):** `diseno/logo/hierro-metal-logo.svg`. Se vectorizó el JPEG: se ampliaron
4 veces los píxeles, se separaron las capas (elipse amarilla, viga blanca, tinta negra) y se trazaron los
contornos; la elipse se ajustó a una elipse real. El resultado conserva el dibujo y el lettering del original
(es un trazo del JPEG, no una tipografía nueva). Fondo transparente, 9 KB.

- **Pendiente:** aprobación del cliente dentro del Hito 2, y pedirle igual el archivo vectorial original si existe
  (sería mejor que este trazo).
- **Uso:** sobre fondo claro (cabecera blanca). La tinta es negra y la viga lleva relleno blanco: sobre negro no se
  lee, por eso el pie conserva la marca en texto «Hierro **Metal** S.R.L.» (igual que el original).
- **Colores:** el SVG usa `var(--color-marca)` y `var(--color-tinta)` con los valores de marca como respaldo, así que
  inline toma los tokens y como `<img>` usa el respaldo. El amarillo tiene un degradado a un amarillo claro
  (`--color-marca-claro`, `#fdf8b8`), como el original.
- **Tamaño:** 64 px de alto en escritorio, 52 px en móvil.

## 8. Motion (con `emil-design-eng`)

| Elemento | Original | Nuevo |
|---|---|---|
| Texto de portada | `hmRise` .6 s (sube 14 px y aparece) | Se conserva |
| Ilustración de portada | estática | Entrada escalonada de las secciones |
| Fichas y líneas | hover de fondo .15 s | + revelado al entrar en viewport, una sola vez |
| FAQ | abre en seco | Animación de altura y giro del "+" |
| Menú móvil | aparece en seco | Despliegue de 200 ms |
| Envío del formulario | — | Estado "enviando" en el botón, transición a página de gracias |

Todo con `prefers-reduced-motion: reduce` respetado (el original ya lo hace; se mantiene).
