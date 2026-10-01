# 03 — Backend: adaptación del panel de Dante

> El backend de Hierro Metal **es el de Dante**, adaptado. Este documento dice qué se toma, qué se
> apaga, qué se agrega y cómo queda el modelo de datos.
>
> ⚠️ La sección 1 se completa en la **Fase 0 leyendo el código de Dante**. Lo que figura abajo
> sale del legajo de Dante y de lo conocido del proyecto (bilingüe ES/DE, panel propio); **no se
> da por cierto hasta verificarlo contra el repo**.

---

## 1. Inventario del repo de Dante (completar en Fase 0)

| Ítem | Esperado según legajo | Verificado en el repo (`~/Dante-Web-Institucional/app`) |
|---|---|---|
| Versión de Laravel / PHP | Laravel 13 / PHP 8.3+ | ✅ Laravel `^13.17`, PHP `^8.3`, Pest 4, Tailwind 4, Vite 8, Alpine 3 |
| Panel | Propio, Blade + Livewire, ruta no adivinable | ⚠️ **Filament `^5.0`** (`app/Filament/{Resources,Pages,Widgets,Blocks}`, `AdminPanelProvider`). Ruta por `DANTE_ADMIN_PATH` (default `panel`) |
| Autenticación | Fortify + 2FA obligatorio | ⚠️ **Sin Fortify.** Auth de Filament. 2FA **por email, opt-in por usuario** (ADR-003 de Dante) con alerta persistente. No es obligatorio |
| Roles | admin / editor | ✅ `spatie/laravel-permission ^8.3` + una Policy por modelo (`app/Policies`) |
| Páginas | Jerárquicas con constructor de bloques | ✅ `Page` con `seo_title`, `seo_description`, `canonical_url`, `is_indexable`. Constructor = `Filament\Forms\Components\Builder` |
| Bloques | hero, texto, galería, CTA, acordeón, tarjetas, video, mapa | ✅ `PageBlocks.php`: hero, texto, imagen_texto, tarjetas, cta, cifras, listado_noticias, galeria, faq, video, testimonios, mapa, formulario, listado_comunicados, documentos, selector_sede (varios son de Dante y no aplican) |
| Noticias | categorías, destacados, portada, fecha | ✅ `Post`, `Category`, además `Announcement`, `CalendarEvent`, `Gallery`, `Document`, `Location` (sobran para Hierro Metal) |
| Medios | biblioteca, conversiones, alt obligatorio | ⚠️ **No es spatie/medialibrary.** Modelo `Media` propio + `MediaUploadService` + `intervention/image ^4.3` + `enshrined/svg-sanitize` (útil para las SVG) |
| Menús | drag & drop, múltiples ubicaciones | ✅ `Menu`, `MenuItem`, `Livewire\ManageMenuItems`, `config/navigation.php` |
| SEO por página | título, descripción, OG, canonical, noindex | ✅ campos en `Page`; `spatie/laravel-sitemap`, `SitemapController`, `RobotsController`. Falta verificar OG |
| Formularios | definición, envíos, mail, CSV | ⚠️ **`FormSubmission` genérico** (`type, name, email, phone, site, message, status, ip_address`), `Actions/Forms/StoreFormSubmission`, `spatie/laravel-honeypot`. Sin rubro, adjuntos ni notas: confirma la decisión de módulo propio |
| Redirecciones | mapa 301 editable | ✅ `Redirect` + middleware + tests de verificación 301 |
| Configuración global | contacto, redes, horarios, IDs analytics | ✅ `SiteSetting`, `HomeSetting`, `IntegrationSetting`, páginas Filament `HomeSettings` e `IntegrationSettings` |
| Auditoría | quién, qué, cuándo | ✅ `spatie/laravel-activitylog` + trait `HasAuditing` + `ActivityLogs` en el panel |
| Traducciones | **ES/DE resuelto en el panel** | ⚠️ `spatie/laravel-translatable` (campos JSON). Locales `['es','it']`, **no DE**. `LocaleController` + `LocaleSwitchTest` |
| Caché de respuesta | invalidada al publicar | ✅ `Services/Cache/PublicContentCache` |
| Respaldos | `spatie/laravel-backup` | ✅ `^10.3`, `config/backup.php`. Falta revisar destino y restauración |
| Integraciones | GA4/GTM, Meta Pixel + CAPI, Turnstile, cookies | ✅ `MetaConversionsApi`, `TurnstileTest`, `CookieConsentTest`, `IntegrationSetting` |
| Sanitización HTML | — | ✅ `ezyang/htmlpurifier` + lista blanca en `config/dante.php` |
| Tests | Pest + Playwright | ✅ 21 archivos Feature + Unit; `@playwright/test` y `@axe-core/playwright` en `package.json`. Cobertura sin medir |

**Sorpresas (impacto en la Fase 3):**

1. **Panel = Filament 5.** Los módulos nuevos son Filament Resources, con Policy y auditoría por
   modelo, siguiendo el patrón existente. Probablemente baja horas; revisar el estimado.
2. **2FA opcional.** La Fase 8 exige 2FA obligatorio: hay que forzarlo en `AdminPanelProvider` para
   este cliente (o registrar una excepción).
3. **Idiomas `es`/`it`**, no DE. Apagar = `locales => ['es']` en `config/dante.php` y ocultar el
   selector de idioma en el panel y el sitio.
4. **`MediaUploadService` no sirve para los adjuntos de cotización.** Valida por MIME real (`finfo`) contra una lista
   fija (imágenes, PDF, docx, xlsx, mp4), **no admite `dwg` ni `dxf`** (su MIME no es fiable), reencodea imágenes, renombra
   con UUID y el tope es 8 MB (la regla 10 pide 10 MB). Los adjuntos van por **un servicio propio** sobre disco privado,
   validado por extensión de la lista blanca + tamaño + sondeo de MIME, nunca ejecutado ni servido sin autenticar.
5. **Bloques de Dante** (selector_sede, comunicados, testimonios…) no aplican: se quitan de
   `PageBlocks` o se dejan sin usar.
6. **Meta CAPI: verificado, ya exige consentimiento.** `StoreFormSubmission` sólo manda el evento si la cookie
   `dante_consent_marketing` vale `1`. El envío incluye correo y teléfono con hash SHA-256, IP y navegador, así que el
   texto de privacidad (`docs/07` §3.11) tiene que decirlo. Para Hierro Metal conviene decidir si se envía el teléfono.
7. **Banner de cookies ya resuelto** en Dante (`cookie-consent.blade.php`, bloquea scripts). Sólo cambia el texto.

### Ejecución local verificada (octubre 2026)

Clon limpio de Dante en una carpeta temporal, PHP 8.3.33 (Homebrew), sin tocar el repo original.

| Paso | Resultado |
|---|---|
| `composer check-platform-reqs` | ✅ todas las extensiones disponibles |
| `composer install` (respeta `composer.lock`) | ✅, pero **falla en un clon limpio** hasta resolver las sorpresas de abajo |
| `npm ci` + `npm audit` | ✅ 0 vulnerabilidades. Sin paquetes de la lista Mini Shai-Hulud. `.npmrc` ya tiene `audit=true` e `ignore-scripts=true` |
| `npm run build` | ✅ 3 s |
| `composer audit` | ⚠️ **3 avisos**: `laravel/framework` 13.26.1 (< 13.30.0, bajo), `league/commonmark` 2.10.0 (≤ 2.10.1, medio y **alto**) |
| Suite Pest | ✅ **215 de 219** pasan (≈ 30 s). Los 4 que fallan son del mapa de redirecciones de WordPress de Dante (`docs/redirecciones-301.csv` ausente): no aplican |

**Sorpresas del arranque** (todas se corrigen en el fork, ninguna toca la lógica):

1. **PHP:** la terminal usa 8.2 y Dante exige ^8.3. Hay que usar 8.3 (Homebrew `php@8.3` o Herd `php83`).
2. **`.env.example` no arranca:** deja vacío `BACKUP_NOTIFICATION_EMAIL`, y `spatie/laravel-backup` lanza «is not a valid email address» al
   arrancar cualquier comando de artisan. Hay que completarlo.
3. **Carpetas ignoradas por git que el código necesita:** `storage/framework/{views,cache,sessions}` (sin ellas: «Please provide a valid
   cache path»), `storage/app/htmlpurifier` (sin ella fallan los tests del sanitizador) y `tests/Unit` (phpunit aborta).
   El fork tiene que versionar un `.gitkeep` en cada una.
4. **Tests con un valor del entorno real:** los de 2FA piden `/panel-dante-2026`, pero el ejemplo trae `DANTE_ADMIN_PATH=panel`. Con
   `DANTE_ADMIN_PATH=panel-dante-2026` pasan. En el fork, el test debe leer `config('dante.admin_path')`.
5. **Dependencias con avisos de seguridad** (arriba). Cero vulnerabilidades es requisito de despliegue: en el fork se corre
   `composer update laravel/framework league/commonmark` y se vuelve a correr la suite. No se probó el update.
6. **Fuentes:** `npm run build` ejecuta un plugin `laravel:fonts`. Hay que revisar si descarga las fuentes en el build (útil
   para autohospedar Barlow y IBM Plex, regla 15) o si llama a Google en runtime.

**2FA, verificado por sus tests:** es opt-in, «nunca es obligatorio, sin importar el usuario» (ADR-003 de Dante), con alerta
persistente mientras no se active. La Fase 8 pide 2FA obligatorio para Hierro Metal: hay que forzarlo en `AdminPanelProvider` y
actualizar esos tests.

**Pendiente de verificar:** Open Graph por página (hay `ogImage` por defecto en el layout, falta confirmar por página), destino de
respaldos (`BACKUP_DESTINATION_DISKS=local` por defecto; la Fase 8 pide almacenamiento externo y una restauración probada).

## 2. Qué se toma, qué se apaga

| Módulo de Dante | Decisión | Por qué |
|---|---|---|
| Auth, 2FA, roles, usuarios | **Se toma igual** | Base de seguridad probada |
| Auditoría | **Se toma igual** | El cliente va a tener más de un editor |
| Medios | **Se toma igual** | Logo, fotos, catálogo PDF |
| Menús | **Se toma igual** | |
| SEO por página | **Se toma igual** y se extiende a familias | |
| Redirecciones | **Se toma igual** | Sitio previo (si existe) |
| Configuración global | **Se toma y se extiende** | Campos de `docs/01` §Datos globales |
| Páginas con bloques | **Se toma** para Calidad y Privacidad (prosa) | Las demás páginas son plantillas fijas alimentadas por módulos |
| Formularios genéricos | **Se reemplaza o se especializa** en *Cotizaciones* | La cotización necesita estados, adjuntos, rubro y seguimiento; un formulario genérico se queda corto. Si el módulo de Dante es extensible, se extiende; si no, módulo propio |
| Noticias | **Se apaga** (se deja el código, se oculta del menú) | No hay contenido de novedades. Queda listo si el cliente lo pide (v1) |
| Traducciones | **Se apaga, no se arranca** | Locale fijo `es` (CLAUDE.md regla 7) |
| Integraciones | **Se toman según D5** | |
| Caché de respuesta, respaldos | **Se toman igual** | |

## 3. Módulos nuevos del dominio

> ⚠️ **Actualizado por la planilla de campos** (`docs/ux-flows/planilla-campos.md` §10): `lineas.medidas` es una lista de tablas (no una), hay mensajes de WhatsApp por contexto, un módulo Vendedores (inactivo por defecto), configuración de correo y cookies ampliada, y metadatos por plantilla. Donde difiera, manda la planilla.

Cada módulo: listado con orden por arrastre, activar/desactivar, edición en el panel, auditoría.

### 3.1 Familias y líneas

```
familias
  id, slug (único), nombre, resumen_home, bajada, ilustracion (enum: chapas|perfiles|tubos|
  varillas|accesorios|ninguna), imagen_id (nullable, medios), orden, activo,
  seo_titulo, seo_descripcion, timestamps

lineas
  id, familia_id → familias, nombre, descripcion, usos (json: string[] máx. 4),
  medidas (json nullable — ver abajo), orden, activo, timestamps
```

- El índice "01 · 7 líneas" se **calcula**: posición de la familia + conteo de líneas activas.
- `medidas` (sólo si D2 = sí): `{ columnas: string[], filas: string[][], nota: string|null }`.
  Editor de tabla simple en el panel (agregar fila/columna, pegar desde Excel). Si está vacío, la
  línea se muestra como en el original.

### 3.2 Servicios y pasos

```
servicios
  id, nombre, descripcion, usos (json), destacado_home (bool), titulo_home, resumen_home,
  orden, activo, timestamps

pasos
  id, titulo, texto, orden, timestamps          -- "De tu plano a la obra"
```

En la home se muestran los servicios con `destacado_home`, con su título/resumen corto
(ej.: "Cortes y plegados" agrupa dos servicios en el original — por eso título propio de home).

### 3.3 Calidad, FAQ, diferenciales, horarios

```
compromisos   id, titulo, texto, orden, activo
faqs          id, pregunta, respuesta (html saneado), orden, activo
diferenciales id, titulo, texto, orden, activo
horarios      id, etiqueta ("Lunes a viernes"), dias (json: [1..7]), abre (time null),
              cierra (time null), cerrado (bool), orden
```

`horarios.dias` + `abre/cierra` permiten calcular "Abierto ahora" (zona `America/Asuncion`) y
generar `openingHoursSpecification` para schema.org sin duplicar datos. La FAQ de horario se
renderiza desde este módulo.

### 3.4 Cotizaciones (el corazón del sitio)

```
cotizaciones
  id, uuid, nombre, empresa, telefono, email, rubro, mensaje,
  origen (url de la página desde la que se envió), utm (json nullable),
  estado (enum: nueva|en_curso|cotizada|ganada|perdida|spam), asignado_a (user_id null),
  notas_internas (text), ip, user_agent, mail_enviado_at (null), timestamps, soft deletes

cotizacion_adjuntos
  id, cotizacion_id, disco, ruta, nombre_original, mime, tamano, timestamps

rubros
  id, nombre, familia_id (null), servicio_id (null), orden, activo
```

**Flujo de guardado (no negociable — CLAUDE.md regla 8):**

```
POST /contacto  → throttle:5,1 → GuardarCotizacionRequest
   ├── honeypot `sitio_web` lleno o timestamp firmado < 3 s → respuesta de éxito falsa, se
   │   guarda con estado `spam` (para medir) y no se notifica
   ├── Cotizacion::create() + adjuntos al disco privado        ← SIEMPRE primero
   ├── dispatch(NotificarNuevaCotizacion)  → cola → mail a la casilla configurada
   │        └── al enviarse, marca mail_enviado_at
   └── redirect /contacto/gracias
```

Panel: bandeja con contador de nuevas, filtros por estado/rubro/fecha, ficha con adjuntos
descargables (sólo autenticado), cambio de estado, notas, asignación, exportación CSV, y un
**botón "Responder por WhatsApp"** que abre `wa.me/{telefono}` con un saludo precargado.

Un comando programado revisa cotizaciones con `mail_enviado_at` nulo después de 15 minutos y
reintenta; si vuelve a fallar, alerta a webparaguay.

### 3.5 Catálogo PDF

Un registro en Configuración: archivo (medios, sólo PDF, ≤ 20 MB), fecha de vigencia, texto del
botón. Ruta pública estable (`/catalogo.pdf`) que siempre sirve la versión vigente — el cliente
reemplaza el archivo y los enlaces no cambian.

## 4. Lo que el frontend lee y de dónde

| Plantilla | Módulos |
|---|---|
| Home | Configuración, diferenciales, familias (resumen_home), servicios destacados, catálogo |
| Productos | familias + líneas |
| Ficha de familia | familia + líneas (+ medidas) |
| Servicios | servicios, pasos |
| Calidad | página con bloques + compromisos |
| FAQ | faqs, horarios |
| Ubicación | Configuración, horarios |
| Contacto | Configuración, rubros, horarios |
| Privacidad | página con bloques |
| Layout | Configuración, menús |

Los textos de encabezado de cada plantilla (rótulo, h1, bajada, CTA) se guardan como **campos
de página** editables, no fijos en Blade.

## 5. Tests mínimos de la Fase 3 (Pest)

- Cada módulo: crear, editar, ordenar, desactivar; sólo usuarios con rol pueden.
- Cotización válida ⇒ registro + adjuntos + job encolado + redirección a gracias.
- **SMTP caído ⇒ la cotización queda guardada** y el reintento la toma.
- Honeypot / envío en < 3 s ⇒ estado `spam`, sin mail.
- Adjunto con extensión fuera de lista blanca o > 10 MB ⇒ rechazo con mensaje claro.
- Adjunto no accesible por URL pública.
- Throttle: el sexto envío en un minuto se rechaza.
- `/catalogo.pdf` sirve el archivo vigente; 404 amable si no hay catálogo cargado.


## 6. Cotizaciones: cómo quedó implementado (Fase 3, octubre 2026)

- **Modelo:** `cotizaciones` (con `uuid`, estados nueva/en_curso/cotizada/ganada/perdida/spam, intentos de aviso y soft delete),
  `cotizacion_adjuntos` y `rubros`. Reemplaza al formulario genérico de Dante (`FormSubmission`), que se eliminó.
- **Orden del flujo (regla 8):** `POST /contacto` valida → `GuardarCotizacion` guarda el pedido y los adjuntos → recién entonces
  encola el aviso. Un fallo de correo, de cola o de un adjunto nunca cuesta el pedido.
- **Anti-spam en capas (regla 9):** campo señuelo `sitio_web` + marca de tiempo firmada `_t` (menos de 3 s, ausente o falsificada =
  spam) + límite de 5 envíos por hora por IP (429 al sexto) + Turnstile (apagado). El spam se **guarda** con estado `spam`, sin
  archivos, sin aviso y sin Meta, y el visitante recibe la misma respuesta que un pedido real.
- **Adjuntos (regla 10):** lista blanca `pdf, jpg, jpeg, png, webp, dwg, dxf, xlsx`, 10 MB, 3 archivos. Se validan por **contenido**:
  firma `%PDF-`, `finfo`+`getimagesize` en imágenes, firma `PK` en xlsx, cabecera `AC10xx` en DWG y `SECTION`/`AutoCAD Binary DXF`
  en DXF (su MIME no es fiable). Se guardan en el disco privado `local` bajo `cotizaciones/{uuid}/{uuid}.ext` y se descargan
  sólo con sesión y permiso `cotizaciones.view`, siempre como archivo (`octet-stream`, `nosniff`). El nombre original sólo se muestra.
- **Aviso por correo:** el SMTP y el remitente salen de `MAIL_*` del `.env` (se cargan después); el destinatario, del panel
  (`email_notificacion_cotizaciones`, hasta 3) con respaldo `SITIO_COTIZACIONES_EMAIL`. Sin destinatario el pedido queda pendiente.
  Reintentos del job (1, 5, 15 y 60 min) y el comando `cotizaciones:reintentar-avisos` cada 5 min; alerta única tras 3 intentos.
  Los archivos no viajan por correo.
- **Plesk sin Supervisor:** el cron de `schedule:run` también procesa la cola (`queue:work --stop-when-empty`) cada minuto.
- **Bandeja:** contador de nuevas en el menú, filtros por estado, rubro, fecha y «aviso sin enviar», spam oculto, búsqueda,
  «Responder por WhatsApp», cambio de estado, asignación, notas y exportación a CSV (con las fórmulas de Excel neutralizadas).
  La auditoría registra el seguimiento y **no** copia los datos personales del pedido.
- **Hallazgo heredado de Dante, corregido:** las cookies de consentimiento las escribe el JavaScript en texto plano y Laravel
  descarta las cookies que no cifró, así que el servidor nunca veía el consentimiento y el evento a Meta no se habría enviado
  jamás. Se eximieron de cifrado (`bootstrap/app.php`).
- **Meta:** por defecto el evento no lleva teléfono ni correo (`SITIO_META_ENVIAR_DATOS=false`), y sólo sale con el consentimiento.
