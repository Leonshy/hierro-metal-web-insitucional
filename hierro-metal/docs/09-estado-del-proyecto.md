# 09 — Estado del proyecto (octubre 2026)

> Foto del proyecto al cierre de la construcción y del primer deploy a staging. Es el documento para
> retomar el trabajo sin haber estado: qué existe, cómo está armado, qué se decidió en el camino, qué se
> verificó y qué falta. El **por qué** original está en `00-legajo-tecnico.md` y `PLAN.md`; las reglas
> permanentes, en `../CLAUDE.md`. Si algo de acá contradice el código, manda el código.

## 1. Dónde está

| Entorno | Dirección | Estado |
|---|---|---|
| Local | `http://localhost:8000` (`php artisan serve`) | Al día con `main` |
| **Staging** | `https://hierrometal.webparaguay.com` (Plesk de webparaguay, PHP 8.3, MySQL) | En línea, `noindex`, con el contenido real cargado |
| Producción | — | **Sin definir** (se decide después de la aprobación del cliente) |

- **Repositorio:** `git@github.com:Leonshy/hierro-metal-web-insitucional.git` (privado). `main` es el trabajo; `deploy-web`
  es el contenido de `web/` como raíz, lo genera `web/deploy/rama-deploy.sh` y es lo único que baja el servidor.
- **Panel:** `/panel` (`SITIO_ADMIN_PATH`). Cuentas: el administrador del cliente (`admin@hierrometal.com.py`) y la
  cuenta protegida de mantenimiento (`webmaster@webparaguay.com`). **Las contraseñas no se guardan en el repo**;
  las tiene el equipo y las de staging son provisorias (cambiarlas antes de producción).
- **Calidad medida al cierre:** 517 tests Pest en verde, PHPStan nivel 5 sin errores (sin ignores ni baseline),
  Pint limpio, `composer audit` y `npm audit` en 0. Lighthouse móvil: accesibilidad 100 y buenas prácticas 100 en
  home, ficha de producto, ubicación, servicios y contacto (el SEO marca 69 sólo por el `noindex` de los entornos
  de prueba; en producción desaparece).

## 2. Cómo está armado

**Stack:** Laravel 13 · PHP 8.3 · MySQL 8 (SQLite en local) · Filament 5.7 (panel) · Livewire 4 (lo usa el panel) ·
Blade + Alpine.js (sitio público) · Vite 8 · spatie (permission, activitylog, backup, sitemap, honeypot,
translatable). El sitio es sólo en español; los textos traducibles se guardan como `{"es": "..."}` para que un
segundo idioma sea configuración y no reconstrucción.

**Modelos de dominio:** `Familia`, `Linea` (con tablas de medidas), `Servicio`, `Paso`, `Compromiso`, `Diferencial`,
`Faq`, `Horario`, `Rubro`, `Cotizacion` (+ `CotizacionAdjunto`), `Page` (páginas de sección y legales), `SeccionInicio`
(orden y activación de las secciones de la portada), `SiteSetting`, `IntegrationSetting`, `Media`, `Menu`/`MenuItem`, `User`.

**Capas que conviene conocer**

- `app/Support/*` — lectura de contenido para las vistas: `Encabezado` (título/bajada/CTA de cada sección),
  `Contacto`, `Catalogo` (PDF), `FranjaCalidad`, `NegocioLocal` (JSON-LD), `TextoEnBloques`.
- `app/Services/Cotizaciones/GuardarCotizacion` — guarda **primero** el pedido y después encola el correo
  (un SMTP caído no pierde pedidos). `ReintentarAvisosCotizacion` reintenta cada 5 min y alerta tras 3 fallos.
- `app/Services/Cache/PublicContentCache` — caché de **consultas** (no de HTML, por el token CSRF), invalidada al
  guardar desde el panel. Incluye la lista de páginas legales del pie.
- `SiteSetting` se lee en bloque (una sola entrada de caché, invalidada al guardar): bajó de 62 a 16 consultas por página.
- `SecurityHeaders` (CSP, HSTS, etc.), `SinCacheEnVistaPrevia`, `PublicMaintenanceMode`.

## 3. El panel

Menú **Contenido**: una pantalla por sección del sitio — Inicio (activar y ordenar las secciones de la portada),
Productos (familias, líneas, medidas, fotos, botón de catálogo), Servicios (servicios y pasos), Calidad (política en
bloques editables + compromisos + franja), Preguntas frecuentes, Ubicación, Contacto. Cada pantalla edita
encabezado, textos y SEO, y tiene **Publicada / Borrador** con botón **Vista previa**.

Menú **Páginas legales**: páginas libres (política de privacidad, términos…) con constructor de bloques.
**Cotizaciones**: bandeja con estados, asignado, notas internas, adjuntos, búsqueda por CI/RUC, exportación CSV.
**Configuraciones**: Generales, Horarios de atención, Rubros del formulario, Catálogo PDF, Usuarios, Integraciones
(GTM/GA4, Meta, Turnstile), Auditoría.

Reglas de comportamiento del panel (verificadas con tests):

- Inicio y Contacto **no se pueden pasar a borrador** (la portada siempre se ve; en Contacto llegan las cotizaciones).
- Un borrador no existe para el público (404, fuera del menú, del pie y del sitemap). Quien tiene sesión del panel lo
  ve en vista previa, sin caché.
- `webmaster@webparaguay.com` es una cuenta **protegida**: no se puede borrar, desactivar ni cambiarle el correo
  (modelo, política y interfaz).

## 4. Decisiones tomadas durante la construcción

- **Hero con foto de fondo** y capa oscura (`.portada-con-foto`) en lugar de foto aparte debajo.
- **Imágenes del catálogo PDF** colocadas en las familias; conversión a WebP con `srcset` (`MediaUploadService`, `x-foto`).
- **Banner de cookies** con la línea gráfica del sitio: Aceptar / Rechazar / elegir, y **Cerrar** sólo cuando ya
  hay una elección guardada. GTM y Meta no cargan hasta que haya consentimiento.
- Se quitó el subtítulo (rótulo) de los encabezados de sección, y se retiraron del panel Noticias, Categorías,
  Redirecciones y Vendedores (no aplican a este cliente).
- **El formulario pide CI o RUC (obligatorio)** — `cotizaciones.ci_ruc`, formato `1.234.567` o `80012345-6`. Se
  muestra en la bandeja, el correo de aviso y el CSV; no se envía a Meta. La política de privacidad lo menciona.
- **El pie lista solas las páginas legales publicadas** (`Page::enlacesLegales()`): al publicar una nueva aparece
  al lado de la anterior, separada por un punto; al pasarla a borrador o borrarla, desaparece.
- Favicon con el logo (`public/favicon.svg`, `favicon.ico`, `apple-touch-icon.png`), también en el panel.
- Cambios de contraste de la auditoría final: rótulo de la tarjeta oscura del catálogo y enlace subrayado en el
  texto de consentimiento del formulario.

## 5. Seguridad, rendimiento y SEO — qué hay

- **Seguridad:** CSP y demás cabeceras; Turnstile configurable desde el panel; honeypot + tiempo mínimo + límite de
  5 envíos/hora por IP; adjuntos con lista blanca, 10 MB, fuera de `public/`; HTML enriquecido saneado (HTMLPurifier);
  CSV sin inyección de fórmulas; roles y permisos, 2FA por correo opcional, ruta del panel configurable; auditoría
  de cambios; respaldos nocturnos; `.env` fuera de git; dependencias auditadas.
- **Rendimiento:** WebP + `srcset`, fuentes propias, assets con hash y caché de 1 año, gzip, consultas agregadas y
  caché de contenido. En local: LCP 1,19 s (home) y 0,64 s (ficha), CLS 0,00.
- **SEO:** sitemap dinámico con `lastmod` real, `robots.txt` por entorno, JSON-LD (`HardwareStore`, `BreadcrumbList`,
  `FAQPage`), Open Graph/Twitter, canonical, títulos ≤ 60 y descripciones ≤ 160 (hay tests que lo exigen).

## 6. Pendiente

**Del cliente o del equipo (no es código)**

- Definir **producción** (servidor y dominio real) y el cutover; hasta entonces staging se queda `noindex`.
- Que el cliente revise la **política de privacidad** (referencia legal, R5 del legajo); en staging ya está publicada.
- Confirmar la **recepción real del correo** de cotización en la casilla del cliente.
- Cargar **IDs de GTM/GA4 y Meta** y las **claves de Turnstile** (si se decide activarlo) en Integraciones, y las
  **coordenadas** del mapa/JSON-LD.
- Capacitación al cliente y manual corto del panel (Fase 10); monitoreo 30 días (Search Console, entregabilidad, spam).

**Técnico, no hecho o no verificado en esta etapa**

- Pentest con `strix` y E2E con Playwright (Fases 8–9 del plan): no se corrieron; la cobertura es Pest + Lighthouse.
- **Prueba de restauración de un respaldo** (los respaldos corren de noche; falta probar restaurar uno).
- Dependencias con versión nueva disponible, sin urgencia (p. ej. Filament 5.9, Pest 5): actualizar en un paso aparte,
  con la suite completa.
- Columnas heredadas de la tabla `pages` que ya no se usan (`site_section`, `site`, `template`, `cover_media_id`).
- El servidor responde `X-Powered-By: PleskLin` (lo agrega Plesk/nginx, no la app); se puede ocultar en el panel de Plesk.

## 7. Cómo se trabaja y se publica

```bash
# Local — antes de commitear (todo en verde, recién ahí se commitea)
cd web
vendor/bin/pint --test && vendor/bin/phpstan analyse --memory-limit=1G && vendor/bin/pest --parallel
composer audit && npm audit

# Publicar a staging (el push siempre se pide y se confirma en el momento)
bash web/deploy/rama-deploy.sh && git push origin main && git push origin deploy-web
npm run build && scp -r public/build/* <usuario>@<host>:httpdocs/public/build/   # sólo si cambió CSS/JS
# En el servidor: git pull --ff-only; composer install --no-dev -o; php artisan migrate --force;
#                 php artisan optimize:clear && config:cache && route:cache && view:cache && event:cache
```

Detalle y trampas del servidor en `08-deploy-plesk.md` §10.

**Reglas de trabajo acordadas**

- Se commitea **después** de la auditoría y el QA completos, nunca antes: un commit con la suite en rojo ya pasó una vez.
- No hay `git push` sin pedido explícito en ese momento. Nadie (ni Claude) ve ni guarda contraseñas: las escribe
  quien las tiene, en el `.env` del servidor o en la sesión SSH.
- El contenido del cliente es la fuente de verdad y se conserva el voseo (ver `../CLAUDE.md`).
