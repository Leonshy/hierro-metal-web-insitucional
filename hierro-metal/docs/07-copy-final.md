# 07 — Copy final (Hito 2)

> **Regla:** el texto del cliente (`docs/01-inventario-contenido.md`) se publica **tal cual**, con voseo
> y todo. Este documento tiene sólo tres cosas: (1) los cambios puntuales al copy del cliente, cada uno
> con su porqué; (2) los textos que el original no tenía y el sitio real necesita; (3) lo que queda
> pendiente. Todo lo que no figura acá no se toca.

---

## 1. Cambios al copy del cliente (puntuales)

| # | Dónde | Dice hoy | Pasa a decir | Por qué |
|---|---|---|---|---|
| K1 | Servicios — título del bloque | **Los cinco servicios** | **Los seis servicios** | La lista tiene seis ítems. Es el cambio mínimo (`docs/04` C1) |
| K2 | Home 04 — bajada | «…te respondemos con precio y disponibilidad en el día. Ventas se centraliza en un único número corporativo.» | «…te respondemos con precio y disponibilidad en el día.» | La segunda frase suena a advertencia dentro de un bloque de venta. El aviso completo (con la explicación) ya está en Contacto. Reversible si el cliente prefiere dejarla (`docs/04` C6) |
| K3 | Varillas — línea «Varillas lisas, de construcción y cuadradas» | «Varilla nervurada para hormigón armado, más varilla lisa y cuadrada para herrería y rejas.» | Se agrega al final: «También varilla roscada.» | El propio catálogo del cliente la ofrece (págs. 16 y 20) y el sitio no la nombra |
| K4 | Rótulo del correo (Home 04, Ubicación, Contacto) | «Administración» | «Correo» | El correo elegido es `hierrometalventas@…`, que es de ventas, no de administración |
| K5 | Privacidad §2 — lista de datos | Nombre, teléfono, empresa, correo y detalle del pedido | Se agrega: «**Los archivos que adjuntes** (plano, despiece o lista), si decidís adjuntar alguno. Se guardan en un espacio privado y sólo los ve el personal que atiende tu pedido.» | El formulario ahora permite adjuntar (M4), y es un dato nuevo que se recopila (`docs/04` C5) |

**No se toca nada más.** Entre otras cosas, quedan igual: titulares, bajadas, diferenciales, descripciones de
líneas y servicios, política de calidad, preguntas frecuentes, pasos «De tu plano a la obra», recuadros
de cierre y el aviso de número único.

## 2. Datos que cambian por las decisiones de `docs/05`

No son ediciones de redacción; son datos de contacto.

| Dato | Antes | Ahora | Dónde aparece |
|---|---|---|---|
| Correo | `admin@hierrometal.com` | `hierrometalventas@hotmail.com` | Home 04, Contacto, Ubicación, pie, FAQ #7, Privacidad §1 y §11 |
| Enlace de Maps | `maps.app.goo.gl/aV2wRxNf9gknmhEz6` | `maps.app.goo.gl/XpbnH8AFw9iJSExs8` | Home 04, Ubicación, Contacto, pie |
| Medidas | No había | Tablas del catálogo 2026 (`docs/05`) | Ficha de cada familia |
| Catálogo PDF | Botón sin destino | Catálogo 2026 | Cabecera, Home, Productos, fichas, Contacto, pie |

---

## 3. Textos nuevos

Escritos con la voz del original: voseo, frases cortas, concretas. Sin adjetivos de relleno.

### 3.1 Ficha de familia (`/productos/{familia}`)

La bajada y las líneas de cada familia **ya existen** en el inventario. Se agrega sólo:

| Elemento | Texto |
|---|---|
| Rótulo de las líneas | «Líneas y medidas» |
| Botón principal | «Pedir cotización de {familia}» · «Descargar catálogo» |
| Cada línea con medidas | Rótulo «Medidas» y, debajo de la tabla: «¿No ves la medida que necesitás? Consultanos con tu lista y te confirmamos stock y precio en el día.» |
| Línea sin medidas en el catálogo | «Consultanos las medidas disponibles y el precio.» · botón «Pedir medidas y precio» |
| Botón por línea | «Cotizar esta línea» |
| Otras familias | «Otras familias» |
| Notas del catálogo (literales) | Laminadas: «Realizamos servicios de cortes sobre medida según la necesidad.» · Inoxidables: «Se puede realizar cortes sobre medida a partir de 1,2 mm de espesor.» |

Nombre de `{familia}` en minúscula en los botones: *chapas*, *perfiles*, *tubos y caños*, *varillas y barras*,
*accesorios de cañería*.

### 3.2 Formulario de cotización

| Situación | Texto |
|---|---|
| Etiqueta de adjuntos | «Plano, despiece o lista (opcional)» |
| Ayuda de adjuntos | «PDF, imagen, DWG, DXF o Excel. Hasta 3 archivos de 10 MB cada uno.» |
| Botón mientras envía | «Enviando tu pedido…» |
| Nombre vacío | «Decinos tu nombre y apellido.» |
| Teléfono vacío | «Dejanos un teléfono o WhatsApp para responderte.» |
| Teléfono inválido | «Revisá el teléfono: parece incompleto.» |
| Correo inválido | «Revisá el correo: parece tener un error.» |
| Detalle vacío | «Contanos qué materiales necesitás.» |
| Detalle muy largo | «El detalle es muy largo. Resumilo o adjuntá un archivo.» |
| Falta aceptar | «Para enviar el pedido tenés que aceptar el uso de tus datos.» |
| Tipo de archivo no permitido | «Ese tipo de archivo no se puede adjuntar. Usá PDF, JPG, PNG, WEBP, DWG, DXF o XLSX.» |
| Archivo muy pesado | «El archivo pesa más de 10 MB. Probá comprimirlo o mandanos el plano por WhatsApp.» |
| Más de 3 archivos | «Podés adjuntar hasta 3 archivos.» |
| Demasiados envíos seguidos | «Enviaste varios pedidos seguidos. Esperá unos minutos o escribinos por WhatsApp.» |
| Error del servidor | «No pudimos enviar tu pedido. Probá de nuevo o escribinos por WhatsApp.» |
| Spam detectado (honeypot) | Sin mensaje: se muestra la página de gracias y el pedido se descarta |

El error siempre aparece junto al campo y **sin borrar lo que escribió la persona**.

### 3.3 Página de gracias (`/contacto/gracias`)

- `rotulo`: Pedido recibido
- `h1`: Recibimos tu pedido
- `bajada`: Te respondemos con precio, disponibilidad y plazo de entrega en el día. Si lo mandaste fuera de horario, te respondemos cuando abramos.
- **Qué pasa ahora** (reutiliza los pasos del cliente): 1. Revisamos tu pedido. 2. Te pasamos precio, disponibilidad y plazo. 3. Retirás por el depósito o lo llevamos a tu obra.
- ¿Con apuro? «Escribinos por WhatsApp al +595 981 320 675.»
- Botones: Escribir por WhatsApp · Ver más productos · Descargar catálogo

### 3.4 Estado de horario («Abierto ahora»)

Se calcula con el módulo Horarios. Los feriados **no** se calculan: el texto fijo «Domingos y feriados: cerrado» sigue visible.

| Estado | Texto |
|---|---|
| Abierto | «Abierto ahora · cerramos a las {hora}» |
| Cerrado, abre hoy | «Cerrado ahora · abrimos hoy a las 07:00» |
| Cerrado, abre mañana | «Cerrado ahora · abrimos mañana a las 07:00» |
| Cerrado, abre el lunes | «Cerrado ahora · abrimos el lunes a las 07:00» |

### 3.5 Mapa

El texto de la nota ya existe en el original («El mapa lo provee Google Maps…»). Se agrega:
botón «Cargar mapa» y botón «Abrir en Google Maps».

### 3.6 WhatsApp con mensaje precargado

| Página | Mensaje |
|---|---|
| Inicio, Contacto | Hola, quiero pedir una cotización. |
| Productos | Hola, quiero consultar por materiales. |
| Ficha de familia | Hola, quiero cotizar {familia}. |
| Servicios | Hola, quiero consultar por un servicio de taller. |
| Calidad | Hola, quiero cotizar y necesito el certificado del fabricante. |
| Preguntas frecuentes | Hola, tengo una consulta. |
| Ubicación | Hola, voy a pasar por el depósito. |
| Gracias | Hola, recién envié un pedido por el sitio. |

El mensaje se identifica con el origen en el panel de cotizaciones y en la medición, si D5 está activo.

### 3.7 Página 404

- `h1`: No encontramos esa página
- `bajada`: Puede que el enlace haya cambiado. Mirá los productos o escribinos y te ayudamos.
- Botones: Ver productos · Escribir por WhatsApp

### 3.8 Metatítulos y metadescripciones

Título ≤ 60 caracteres, descripción ≤ 160. Sin números ni afirmaciones que el cliente no haya hecho.

| Página | Título | Descripción |
|---|---|---|
| Inicio | Hierro Metal S.R.L. · Chapas, perfiles y tubos de acero | Importación y venta mayorista y minorista de chapas, perfiles, tubos, varillas y accesorios de acero en Fernando de la Mora. Corte a medida y entrega en obra. |
| Productos | Productos y catálogo · Hierro Metal S.R.L. | Cinco familias de materiales metálicos con stock permanente: chapas, perfiles, tubos, varillas y accesorios. Descargá el catálogo y pedí tu cotización. |
| Chapas | Chapas de acero · Hierro Metal S.R.L. | Chapas laminadas en frío y caliente, galvanizadas, termoacústicas, inoxidables, antideslizantes y desplegadas, con certificado del fabricante. |
| Perfiles | Perfiles y estructurales · Hierro Metal S.R.L. | Perfiles IPN, UPN, C y U, ángulos, planchuelas y hierro Tee. Perfiles C y U en chapa doblada a medida. Pedí tu cotización. |
| Tubos | Tubos y caños · Hierro Metal S.R.L. | Caños redondos, cuadrados y rectangulares y tubos estructurales con y sin costura, SCH10 a SCH80. Consultá medidas y pedí tu cotización. |
| Varillas | Varillas y barras · Hierro Metal S.R.L. | Varillas lisas, de construcción, cuadradas y roscadas, barras trafiladas SAE 1045, 1050 y 1060 y barras de bronce. Pedí tu cotización. |
| Accesorios | Accesorios de cañería · Hierro Metal S.R.L. | Válvulas, codos, tee, bridas, bujes y uniones en acero inoxidable, galvanizado, acero al carbono y línea anti-incendio. Pedí tu cotización. |
| Servicios | Servicios industriales · Hierro Metal S.R.L. | Cortes a medida, plegados, perforado, perfiles C y U especiales, galvanización y entrega en obra con flota propia de camiones. |
| Calidad | Política de calidad · Hierro Metal S.R.L. | Materia prima certificada bajo Normas Internacionales del Acero, control de medidas antes de despachar y certificado del fabricante en las chapas. |
| Preguntas frecuentes | Preguntas frecuentes · Hierro Metal S.R.L. | Venta mayorista y minorista, corte a medida, entrega en obra, certificados, horarios y cómo pedir una cotización en Hierro Metal S.R.L. |
| Ubicación | Ubicación y horarios · Hierro Metal S.R.L. | Depósito, taller y atención comercial en Pedro Getto esq. Cadete Sisa, Fernando de la Mora. Lunes a viernes 07:00 a 17:00 y sábados 07:00 a 12:00. |
| Contacto | Pedí tu cotización · Hierro Metal S.R.L. | Mandanos tu lista de materiales, el plano o el despiece y te respondemos con precio, disponibilidad y plazo de entrega en el día. |
| Privacidad | Política de privacidad · Hierro Metal S.R.L. | Cómo tratamos los datos que nos dejás en el formulario de cotización de Hierro Metal S.R.L. |
| Gracias | Pedido recibido · Hierro Metal S.R.L. | `noindex`, sin descripción |

### 3.9 Textos alternativos de las ilustraciones

- **Portada:** se conserva el del original: «Secciones de acero: perfiles IPN y UPN, ángulo, caño redondo, tubos cuadrado y rectangular, y varillas de construcción».
- **Ilustraciones de familia (5):** **decorativas**, igual que en el original (`aria-hidden`): el nombre de la
  familia está en el texto de al lado. No llevan alt.
- **Logo:** «Hierro Metal S.R.L.».

### 3.10 Banner de consentimiento de cookies (D5 = sí)

Dante ya trae el componente (`cookie-consent.blade.php`): bloquea GA4 y Meta hasta que haya consentimiento y
«Rechazar todo» tiene el mismo peso visual que «Aceptar todo». Sólo cambia el texto:

| Elemento | Texto |
|---|---|
| Aviso | «Usamos cookies para medir cuánta gente visita el sitio y para saber si nuestros anuncios funcionan. Podés aceptar todas, rechazar todas o elegir cuáles.» · enlace «Más información en la política de privacidad» (a Privacidad §8) |
| Botones | Rechazar todo · Configurar · Aceptar todo |
| Al configurar | «Elegí qué cookies permitís. Las necesarias no se pueden desactivar.» |
| Categorías | Necesarias (siempre activas) · Medición de visitas (Google Analytics) · Publicidad (Meta: Facebook e Instagram) |
| Botones al configurar | Rechazar todo · Guardar preferencias |
| Botón para reabrir | «Cookies» |

El banner no puede tapar el botón de WhatsApp ni la cabecera: en móvil se ancla abajo y deja libre el WhatsApp flotante.

### 3.11 Privacidad: borrador de los cambios por D5

> ⚠️ **Borrador para el asesor legal del cliente.** webparaguay no da consejo legal. Se parte del texto del cliente
> y se agrega lo mínimo. Lo que no aparece acá queda igual.

- **§2 Qué datos recopilamos — agregar al final:** «Si aceptás las cookies de medición o de publicidad, también se
  registran datos de navegación: páginas que visitás, tipo de dispositivo y de navegador, y de dónde llegaste al sitio.»
- **§3 Para qué los usamos — agregar:** «Los datos de navegación los usamos para entender cómo se usa el sitio y para
  medir si nuestros anuncios funcionan. No los usamos para identificarte.»
- **§5 Con quién los compartimos — agregar dos ítems:**
  - «Google, a través de Google Analytics, si aceptás las cookies de medición.»
  - «Meta (Facebook e Instagram), si aceptás las cookies de publicidad.»
- **§8 Cookies — reemplazar** el texto actual por: «Este sitio usa cookies técnicas necesarias para que funcione el formulario,
  que no se pueden desactivar. Las cookies de medición (Google Analytics) y de publicidad (Meta) sólo se instalan si las
  aceptás en el aviso que aparece al entrar. Podés cambiar tu decisión en cualquier momento desde el botón «Cookies».
  El mapa de Google, en la página de ubicación, puede instalar cookies de terceros al cargarse.»
  *(La frase del cliente «si en el futuro incorporamos herramientas de medición… actualizaremos esta política» queda
  cumplida con este cambio.)*
- **Última actualización:** la fecha de publicación.

**Regla técnica para la Fase 6 (verificada en el código de Dante):** la API de conversiones de Meta del lado servidor sólo se envía
si la cookie `dante_consent_marketing` vale `1`, es decir, con el consentimiento de «Publicidad». Ese envío incluye correo y teléfono
(con hash SHA-256), IP y navegador, y el borrador de §3.11 ya lo cubre al decir que Meta recibe datos si se aceptan las cookies de
publicidad. Decisión pendiente: si se quiere enviar el teléfono o sólo el evento.

---

## 4. Pendientes

1. **Privacidad, §4.** Cita la Ley 6534/2020 (de datos *crediticios*) y no se corrige acá: lo revisa el asesor legal
   del cliente (`docs/04` C3). El borrador de §3.11 (D5) viaja en la misma revisión. **La página de privacidad no se
   publica hasta tener esa revisión.**
2. **Cuentas.** Hacen falta el ID de GA4 o GTM y la cuenta de Meta Business (Pixel y token de la API). Sin ellos, las
   integraciones quedan instaladas y apagadas.
3. **Eventos a medir:** llegada a `/contacto/gracias` y clic en WhatsApp (con la página de origen).
4. **Aprobación del cliente.** Los cinco cambios de §1 y los textos de §3 se le muestran en el Hito 2 junto con
   las correcciones del catálogo (`docs/06`).
