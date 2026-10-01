# 01 — Inventario de contenido

> Contenido extraído literalmente de `referencia/sitio-cliente-original.html`.
> **Es la fuente de verdad de los seeders.** Si un texto cambia en el Hito 2, se cambia acá y se
> registra en `docs/04-mejoras.md`. Nada se inventa.
>
> Convención: `[campo]` = nombre de campo sugerido en el panel.

---

## Datos globales (Configuración)

| Campo | Valor |
|---|---|
| `razon_social` | Hierro Metal S.R.L. |
| `descripcion_corta` | Importación y venta de materiales de construcción metálicos y metalúrgicos. |
| `direccion_corta` | Pedro Getto esq. Cadete Sisa |
| `direccion_larga` | Pedro Getto esquina Cadete Sisa, Fernando de la Mora, departamento Central, Paraguay |
| `ciudad` | Fernando de la Mora |
| `departamento` | Central |
| `telefono_ventas` | +595 981 320 675 |
| `telefono_href` | `tel:+595981320675` |
| `whatsapp` | `https://wa.me/595981320675` |
| `email_contacto` | hierrometalventas@hotmail.com *(antes admin@hierrometal.com; decisión 9 de `docs/05`)* |
| `maps_url` | https://maps.app.goo.gl/XpbnH8AFw9iJSExs8 |
| `instagram` | https://www.instagram.com/hierrometalsrl/ — @hierrometalsrl |
| `facebook` | https://www.facebook.com/p/Hierro-Metal-SRL-100044982888002/?locale=es_LA — Hierro Metal SRL |
| `aviso_numero_unico` | **Ventas se centraliza en un único número corporativo.** Si alguien te contacta desde otro número diciendo representar a Hierro Metal S.R.L., verificá con nosotros antes de operar. |

### Horarios

| Día | Horario |
|---|---|
| Lunes a viernes | 07:00 — 17:00 |
| Sábados | 07:00 — 12:00 |
| Domingos y feriados | Cerrado |

### Navegación

- **Escritorio:** Productos · Servicios · Calidad · FAQ · Ubicación · Contacto · [CTA] Pedir cotización
- **Móvil:** Productos y catálogo · Servicios industriales · Política de calidad · Preguntas frecuentes · Ubicación · Contacto · (+595 981 320 675 · Correo)
- **Barra superior:** dirección "Pedro Getto esq. Cadete Sisa · Fernando de la Mora, Central" · teléfono · Instagram · Facebook

### Pie

- Marca: "Hierro / Metal **S.R.L.**" + `descripcion_corta`
- Col. 2: Productos y catálogo · Servicios industriales · Política de calidad · Preguntas frecuentes
- Col. 3: Ubicación · Contacto · Descargar catálogo
- Col. 4: teléfono · correo · "Pedro Getto esq. Cadete Sisa, Fernando de la Mora, Central →" (a Maps)
- Legal: "© {año} Hierro Metal S.R.L. · Todos los derechos reservados" · Política de privacidad

### WhatsApp flotante

Texto "WhatsApp" · aria-label "Escribir por WhatsApp al +595 981 320 675".

---

## Página: Inicio (`/`)

### Portada

- `insignia`: Importación y venta · Mayorista y minorista
- `h1`: Materiales metálicos para construir Paraguay
- `bajada`: Chapas, perfiles, tubos, varillas y accesorios de acero con stock permanente. Cortamos, plegamos, perforamos y galvanizamos según tu plano, y entregamos en obra con flota propia de camiones.
- CTA 1: Pedir cotización → contacto
- CTA 2: Ver productos y medidas → productos
- `nota`: Todas las chapas con certificado de calidad del fabricante
- Ilustración: `ilustracion-portada.svg` — aria-label "Secciones de acero: perfiles IPN y UPN, ángulo, caño redondo, tubos cuadrado y rectangular, y varillas de construcción"

### Diferenciales (franja amarilla) — módulo `diferenciales`

| Título | Texto |
|---|---|
| Stock permanente | Medidas comerciales todo el año. |
| Importación directa | Precio mayorista y minorista. |
| Corte a medida | Cortes, plegados y perforaciones. |
| Flota propia | Entrega de materiales en obra. |

### 01 — Productos

- `rotulo`: 01 — Productos
- `titulo`: Qué comercializamos
- `bajada`: Cinco familias de materiales metálicos y metalúrgicos con stock permanente. Entrá al catálogo para ver espesores, medidas y normas de cada línea.
- Fichas: una por familia (ver módulo Familias, campo `resumen_home`) + ficha destacada:
  - **Catálogo de materiales** — Nuestro catálogo general en PDF. Confirmá medidas, espesores y stock actual por WhatsApp antes de comprar. — Botón "Descargar PDF"

### 02 — Servicios industriales (fondo negro)

- `titulo`: Trabajamos el material por vos
- `bajada`: Cortes sobre medida, plegados, perforaciones, fabricación especial de perfiles C y U, galvanización y entrega en obra con flota propia.
- Destacados (servicios con `destacado_home`):

| Título | Texto |
|---|---|
| Cortes y plegados | Chapas y barras según tu plano o lista de despiece. |
| Fabricación especial | Perfiles C y U en chapa doblada, a medida. |
| Galvanización | Protección contra la corrosión de chapas y perfilería. |

- Botón: Ver los servicios

### 03 — Política de calidad (franja amarilla)

- `titulo`: Materia prima certificada bajo Normas Internacionales del Acero
- `bajada`: Control de calidad estricto, infraestructura mantenida y mejora continua de productos y procesos.
- Botón negro: Leer la política completa

### 04 — Contacto

- `titulo`: ¿Necesitás un presupuesto?
- `bajada`: Cargá tu lista de materiales y te respondemos con precio y disponibilidad en el día. *(K2: se quitó «Ventas se centraliza en un único número corporativo.»; el aviso completo sigue en Contacto)*
- CTA: Pedir cotización · Preguntas frecuentes
- Datos: Ventas · WhatsApp y teléfono / Correo / Dirección · Fernando de la Mora → "Pedro Getto esq. Cadete Sisa →"

---

## Módulo: Familias y líneas (`/productos`)

### Encabezado de página

- `rotulo`: Productos
- `h1`: Materiales metálicos y metalúrgicos
- `bajada`: Cinco familias con stock permanente en nuestro depósito de Fernando de la Mora. Trabajamos con importación directa, por eso vendemos tanto al por mayor como al por menor. Las medidas y espesores disponibles varían según la línea y el momento: escribinos con tu lista y te confirmamos stock y precio en el día.
- Índice: 01 · Chapas · 02 · Perfiles · 03 · Tubos y caños · 04 · Varillas y barras · 05 · Accesorios
- CTA: Pedir cotización · Descargar catálogo PDF

### 01 — Chapas de acero · slug `chapas` · ilustración `chapas`

- `resumen_home`: Laminadas en frío y caliente, galvanizadas, termoacústicas, inoxidables, antideslizantes, desplegadas y para techo.
- `bajada`: Para cubiertas, cerramientos, estructuras, carrocerías, pisos industriales y trabajos de herrería. Todas nuestras chapas llegan con el certificado de calidad del fabricante.

| Línea | Descripción | Usos |
|---|---|---|
| Laminadas en frío y en caliente | Chapa negra en distintos espesores, base de casi todo trabajo de herrería y estructura metálica. | Estructuras · Herrería · Carrocerías |
| Galvanizadas | Con recubrimiento de zinc para resistir la corrosión en ambientes húmedos o a la intemperie. | Cerramientos · Intemperie |
| Para techo (trapezoidal y ondulada) | Láminas metálicas para cubiertas: durables, livianas, fáciles de instalar y disponibles en varios colores. | Techos · Galpones · Tinglados |
| Termoacústicas | Paneles con aislación que reducen el calor y el ruido de la lluvia respecto de una chapa simple. | Techos habitables · Depósitos |
| Inoxidables | Acero inoxidable para ambientes exigentes en higiene o con exposición química permanente. | Alimentación · Industria · Mobiliario |
| Antideslizantes | Chapa con relieve tipo damero, para superficies transitables donde se necesita agarre. | Escaleras · Pisos · Plataformas |
| Desplegadas | Chapa cortada y estirada en forma de malla, liviana y con paso de aire y luz. | Rejas · Cerramientos · Protecciones |

### 02 — Perfiles y estructurales · slug `perfiles` · ilustración `perfiles`

- `resumen_home`: Perfil IPN y UPN, perfil C y U en chapa doblada, ángulos, planchuelas y hierro Tee.
- `bajada`: La perfilería que sostiene la obra. Además de las medidas comerciales de stock, fabricamos perfiles C y U en chapa doblada según la medida que necesites.

| Línea | Descripción | Usos |
|---|---|---|
| Perfil IPN y UPN | Perfiles laminados doble T y U, para vigas, columnas y estructuras portantes. | Vigas · Columnas · Entrepisos |
| Perfil C y U en chapa doblada | Perfilería conformada en frío. La fabricamos a medida en nuestro taller a partir de tu plano. | Correas · Bastidores · Steel framing |
| Ángulos, planchuelas y hierro Tee | Complementos de estructura y herrería en distintas secciones y espesores. | Herrería · Refuerzos · Marcos |

### 03 — Tubos y caños · slug `tubos` · ilustración `tubos`

- `resumen_home`: Caños redondos, cuadrados y rectangulares; tubos estructurales con y sin costura SCH10 a SCH80.
- `bajada`: Tubería estructural y de conducción, en acabado profesional y amplia gama de diámetros y espesores de pared.

| Línea | Descripción | Usos |
|---|---|---|
| Caños redondos, cuadrados y rectangulares | Tubería metálica para estructuras livianas, cerramientos, barandas y mobiliario. | Estructuras · Barandas · Portones |
| Tubos estructurales con y sin costura · SCH10 a SCH80 | Distintos schedules de espesor de pared, para conducción y estructuras que trabajan a presión o carga. | Conducción · Industria · Estructura pesada |

### 04 — Varillas y barras · slug `varillas` · ilustración `varillas`

- `resumen_home`: Varillas lisas, de construcción y cuadradas; barras trafiladas SAE 1045/1050/1060 y barras de bronce.
- `bajada`: Desde la varilla de construcción de obra civil hasta barras trafiladas para mecanizado.

| Línea | Descripción | Usos |
|---|---|---|
| Varillas lisas, de construcción y cuadradas | Varilla nervurada para hormigón armado, más varilla lisa y cuadrada para herrería y rejas. También varilla roscada. *(K3)* | Hormigón armado · Rejas · Herrería |
| Barras trafiladas SAE 1045, 1050 y 1060 | Aceros al carbono de mayor resistencia, con tolerancia dimensional apta para mecanizado. | Ejes · Piezas mecanizadas · Repuestos |
| Barras de bronce | Para bujes, casquillos y piezas donde se necesita menor fricción y buena maquinabilidad. | Bujes · Casquillos · Mantenimiento |

### 05 — Accesorios de cañería · slug `accesorios` · ilustración `accesorios`

- `resumen_home`: Válvulas, codos, tee, bridas, bujes y uniones en inoxidable, galvanizado, acero al carbono y anti-incendio.
- `bajada`: Todo lo que une, deriva y corta una línea de cañería, en los cuatro materiales que más se piden en obra e industria.

| Línea | Descripción | Usos |
|---|---|---|
| Válvulas, codos, tee, bridas, bujes y uniones | Disponibles en acero inoxidable, galvanizado, acero al carbono y línea anti-incendio. | Instalaciones · Industria · Anti-incendio |

> El rótulo "NN — N líneas" y el índice "01 · 7 líneas" se **calculan** a partir del orden y la
> cantidad de líneas activas. No son campos editables.

### Cierre de Productos (fondo negro)

- `rotulo`: Siguiente paso
- `titulo`: Pasanos tu lista de materiales
- `bajada`: Mandanos el despiece, el plano o simplemente la lista escrita. Te respondemos con precio, disponibilidad y plazo de entrega en el día.
- CTA: Pedir cotización · Escribir por WhatsApp
- Recuadros: **¿Necesitás corte a medida?** Cortamos, plegamos y perforamos el material antes de entregarlo. / **¿Lo llevamos a la obra?** Entregamos con flota propia de camiones.

---

## Página: Servicios (`/servicios`)

### Encabezado

- `rotulo`: Servicios industriales
- `h1`: Trabajamos el material por vos
- `bajada`: No vendemos solamente el material: lo preparamos. Cortamos, plegamos, perforamos, fabricamos perfilería a medida, galvanizamos y lo dejamos en tu obra. Así llegás al montaje con las piezas listas y sin desperdicio.
- CTA: Pedir cotización · Ver productos

### Qué hacemos — módulo `servicios`

- `titulo`: **Los seis servicios** *(K1: el original decía «cinco»; ver `docs/07`)*

| Servicio | Descripción | Usos |
|---|---|---|
| Cortes a medida | Cortamos chapas, barras, perfiles y tubos según tu plano o tu lista de despiece. Pagás el material que vas a usar y llegás a la obra sin tener que cortar en el lugar. | Chapas · Barras · Perfiles · Tubos |
| Plegados | Doblamos la chapa a los ángulos que necesites, para cerramientos, cubiertas, babetas, cenefas y piezas de terminación. | Cubiertas · Cerramientos · Terminaciones |
| Perforado | Hacemos las perforaciones donde las marca el plano, listas para bulonar en el montaje. | Uniones bulonadas · Montaje |
| Fabricación especial de perfiles | Fabricamos perfiles C y U en chapa doblada en la medida y el espesor que pidas, cuando la medida comercial no te sirve. | Correas · Bastidores · Medidas no comerciales |
| Galvanización | Recubrimiento de zinc sobre chapas y perfilería para protegerlas de la corrosión, especialmente en obras a la intemperie o en ambientes húmedos. | Intemperie · Ambientes húmedos · Larga duración |
| Entrega en obra | Tenemos flota propia de camiones: coordinamos el día y dejamos el material en el lugar, sin depender de fletes de terceros. | Flota propia · Todo Paraguay |

### Cómo trabajamos — módulo `pasos` (fondo negro)

- `titulo`: De tu plano a la obra
- `bajada`: Un pedido con servicio de taller sigue siempre los mismos cuatro pasos.

| # | Paso | Texto |
|---|---|---|
| 01 | Nos mandás el pedido | Plano, lista de despiece o el detalle escrito por WhatsApp, correo o el formulario del sitio. |
| 02 | Cotizamos | Te pasamos precio del material más el trabajo de taller, la disponibilidad y el plazo. En el día. |
| 03 | Preparamos el material | Cortamos, plegamos, perforamos o galvanizamos según lo acordado, y controlamos las medidas. |
| 04 | Entregamos | Retirás por el depósito o lo llevamos a la obra con nuestros camiones, en la fecha coordinada. |

- CTA: Empezar un pedido · Escribir por WhatsApp

### Calidad (franja amarilla)

- `titulo`: El trabajo de taller se controla igual que el material
- `bajada`: Materia prima certificada bajo Normas Internacionales del Acero, control de medidas antes de despachar e infraestructura mantenida.
- Botón: Leer la política de calidad

---

## Página: Calidad (`/calidad`)

### Encabezado (fondo amarillo)

- `rotulo`: Política de calidad
- `h1`: Materia prima certificada bajo Normas Internacionales del Acero
- `bajada`: El acero no se compra por el precio de la tonelada: se compra por lo que aguanta. Por eso todo lo que entra a nuestro depósito llega con el respaldo del fabricante.

### Introducción

En **Hierro Metal S.R.L.** importamos y comercializamos materiales de construcción metálicos y metalúrgicos. Nuestro compromiso es que cada chapa, perfil, tubo, varilla y accesorio que sale de nuestro depósito cumpla con lo que el cliente necesita para su obra, y que pueda demostrarlo con documentación del fabricante.

### Nuestros compromisos — módulo `compromisos`

| Título | Texto |
|---|---|
| Materia prima certificada | Trabajamos con materia prima certificada bajo Normas Internacionales del Acero. Las chapas se entregan con el certificado de calidad del fabricante. |
| Control de calidad estricto | Controlamos el material al recibirlo y verificamos las medidas de los trabajos de taller antes de despachar cada pedido. |
| Infraestructura mantenida | Mantenemos el depósito, las máquinas de corte y plegado y la flota de camiones en condiciones de operar de forma segura y precisa. |
| Mejora continua | Revisamos de forma permanente nuestros productos y procesos para responder mejor y más rápido a lo que pide el mercado. |

### Qué significa esto para tu obra (texto enriquecido)

**Sabés qué acero estás comprando** — Cuando el material viene certificado, sabés qué resistencia tiene y podés justificarlo ante una dirección de obra, una fiscalización o un cliente final. Si necesitás el certificado de una partida en particular, pedilo al momento de cotizar y lo entregamos con el material.

**Las medidas llegan como las pediste** — Los cortes, plegados y perforaciones se verifican contra el plano o la lista de despiece antes de cargar el camión. Es el paso que evita tener que rehacer piezas en el lugar de montaje.

**Si algo no está bien, lo resolvemos** — Si recibís un material que no corresponde con lo cotizado o con lo que pediste, avisanos apenas lo detectes. Es la única manera de corregirlo rápido y de que el problema no se repita con el próximo pedido.

### Ámbito de aplicación

Esta política alcanza a todas nuestras actividades: la importación y compra de materiales, el almacenamiento en depósito, los servicios de corte, plegado, perforado, fabricación de perfiles y galvanización, y la entrega en obra con flota propia.

- CTA: Pedir cotización · Ver productos

---

## Página: Preguntas frecuentes (`/preguntas-frecuentes`) — módulo `faqs`

- `rotulo`: Preguntas frecuentes
- `h1`: Lo que más nos preguntan
- `bajada`: Si tu consulta no está acá, escribinos por WhatsApp y te respondemos.

| # | Pregunta | Respuesta |
|---|---|---|
| 1 | ¿Venden al por mayor y al por menor? | Sí. Trabajamos con importación directa, lo que nos permite vender tanto al por mayor como al por menor. Atendemos a constructoras, herrerías e industrias, y también a clientes particulares que necesitan una cantidad chica. |
| 2 | ¿Cortan el material a medida? | Sí. Cortamos, plegamos y perforamos chapas, barras, perfiles y tubos según tu plano o tu lista de despiece. Así pagás solo el material que vas a usar y llegás al montaje con las piezas listas. ¶ Podés ver el detalle en la página de [servicios industriales](/servicios). |
| 3 | ¿Entregan el material en obra? | Sí. Tenemos flota propia de camiones: coordinamos el día de entrega y dejamos el material en el lugar, sin depender de fletes de terceros. |
| 4 | ¿Las chapas vienen con certificado de calidad? | Sí. Trabajamos con materia prima certificada bajo Normas Internacionales del Acero, y las chapas se entregan con el certificado de calidad del fabricante. ¶ Si necesitás el certificado de una partida en particular para presentar ante una dirección de obra o una fiscalización, pedilo al momento de cotizar y lo entregamos junto con el material. |
| 5 | ¿Fabrican perfiles en medidas no comerciales? | Sí. Fabricamos perfiles C y U en chapa doblada en la medida y el espesor que necesites, para cuando la medida comercial de stock no se adapta a tu proyecto. |
| 6 | ¿Ofrecen galvanización? | Sí. Galvanizamos chapas y perfilería para protegerlas de la corrosión. Es especialmente recomendable en obras a la intemperie o en ambientes húmedos, donde el acero sin protección se deteriora rápido. |
| 7 | ¿Cómo pido una cotización? | De tres maneras, la que te quede más cómoda: completás el [formulario de contacto](/contacto), nos escribís por WhatsApp al [+595 981 320 675](https://wa.me/595981320675), o nos mandás un correo a [hierrometalventas@hotmail.com](mailto:hierrometalventas@hotmail.com). ¶ Mandanos tu lista de materiales, el plano o el despiece y te respondemos con precio, disponibilidad y plazo de entrega. |
| 8 | ¿Cuál es el horario de atención? | De lunes a viernes, de 07:00 a 17:00. Los sábados, de 07:00 a 12:00. *(renderizar desde el módulo Horarios, no duplicar)* |
| 9 | ¿Dónde están ubicados? | Nuestra casa matriz está en Pedro Getto esquina Cadete Sisa, Fernando de la Mora, departamento Central. Podés ver el mapa y cómo llegar en la página de [ubicación](/ubicacion). |
| 10 | ¿A qué zonas del país llegan? | Operamos en todo Paraguay. Para entregas en el interior, avisanos al momento de cotizar y coordinamos el envío según el volumen del pedido. |

- CTA: Pedir cotización · Escribir por WhatsApp

---

## Página: Ubicación (`/ubicacion`)

- `rotulo`: Ubicación
- `h1`: Casa matriz en Fernando de la Mora
- `bajada`: Depósito, taller y atención comercial en el mismo lugar. Podés venir a retirar tu pedido o coordinamos la entrega en tu obra con nuestros camiones.
- Dirección: **Pedro Getto esq. Cadete Sisa** — Fernando de la Mora, departamento Central, Paraguay.
- CTA: Cómo llegar (Maps) · Llamar antes de venir
- Horarios (módulo)
- Datos: Ventas · Correo · Instagram · Facebook
- Mapa: `rotulo` Mapa · `titulo` Dónde estamos · nota: El mapa lo provee Google Maps. Al cargarlo, Google puede registrar datos de tu navegación; podés ver el detalle en nuestra política de privacidad.
- Franja amarilla: **¿Preferís que lo llevemos nosotros?** Tenemos flota propia de camiones y entregamos en obra en todo Paraguay. — Pedir cotización

---

## Página: Contacto (`/contacto`)

- `rotulo`: Contacto
- `h1`: Pedí tu cotización
- `bajada`: Mandanos tu lista de materiales, el plano o el despiece y te respondemos con precio, disponibilidad y plazo de entrega en el día. Si preferís hablar, escribinos por WhatsApp o llamanos.

### Formulario — `titulo`: Contanos qué necesitás

| Campo | Tipo | Requerido | Límite | Ayuda / placeholder |
|---|---|---|---|---|
| `nombre` — Nombre y apellido | text | sí | 120 | — |
| `empresa` — Empresa u obra | text | no | 120 | — |
| `telefono` — Teléfono o WhatsApp | tel | sí | 40 | `09xx xxx xxx` |
| `email` — Correo electrónico | email | no | 150 | Opcional, pero nos ayuda a mandarte la cotización por escrito. |
| `rubro` — ¿Qué necesitás? | select | no | — | "Elegí una opción" |
| `mensaje` — Lista de materiales o detalle del pedido | textarea | sí | 4000 | Cuanto más detalle nos des (medidas, espesores, cantidades, zona de entrega), más rápido te cotizamos. |
| `sitio_web` | honeypot | — | — | oculto |

**Placeholder del mensaje:**
```
Ejemplo:
- 20 chapas trapezoidales galvanizadas, largo 6 m
- 10 perfiles UPN 100, 12 m
- Corte a medida según plano
- Entrega en obra, zona Luque
```

**Opciones de rubro** (módulo configurable):
Chapas de acero · Perfiles y estructurales · Tubos y caños · Varillas y barras · Accesorios de cañería · Servicio de corte, plegado o perforado · Galvanización · Varios materiales / lista completa · Otra consulta

- Consentimiento: Al enviar, aceptás que usemos tus datos para responder esta consulta. Ver la política de privacidad.
- Botón: **Enviar pedido**

### Canales directos — `titulo`: O escribinos ahora

Ventas · WhatsApp y teléfono / Correo / Depósito y taller / Horario de atención · CTA: Escribir por WhatsApp · Descargar catálogo · `aviso_numero_unico`

---

## Página: Política de privacidad (`/privacidad`)

> Texto completo en el original (`#pagina-privacidad`), 11 secciones: 1. Quiénes somos ·
> 2. Qué datos recopilamos · 3. Para qué los usamos · 4. Base legal · 5. Con quién los
> compartimos · 6. Cuánto tiempo los conservamos · 7. Tus derechos · 8. Cookies · 9. Seguridad ·
> 10. Cambios en esta política · 11. Contacto. "Última actualización: septiembre de 2026."
>
> Se carga como **página de texto enriquecido** del panel, copiando el HTML del original.
> ⚠️ **No publicar sin revisar** las secciones 2, 4, 5 y 8 — ver legajo R5 y `docs/04-mejoras.md` C3.
>
> **Cambios del Hito 2 sobre el texto original:** K4 la etiqueta «Administración» pasa a «Correo» (ya aplicado arriba); K5 en §2 se agrega el ítem «Los archivos que adjuntes (plano, despiece o lista), si decidís adjuntar alguno. Se guardan en un espacio privado y sólo los ve el personal que atiende tu pedido.»; y en §1 y §11 el correo pasa a `hierrometalventas@hotmail.com`. Ver `docs/07`.
