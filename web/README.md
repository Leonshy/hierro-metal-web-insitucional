# Hierro Metal S.R.L. — web

Sitio institucional y de captación de cotizaciones. Laravel 13, PHP 8.3+, Filament 5 (panel), Alpine, Tailwind v4 y
Vite. Derivado del panel de Dante (fork limpio, `../hierro-metal/docs/adr/0001`).

- **Reglas y contexto:** `../hierro-metal/CLAUDE.md` · **Plan:** `../hierro-metal/PLAN.md`
- **Estado actual, decisiones y pendientes:** `../hierro-metal/docs/09-estado-del-proyecto.md`
- **Deploy (Plesk, staging y trampas):** `../hierro-metal/docs/08-deploy-plesk.md`
- **Staging:** https://hierrometal.webparaguay.com (`noindex`)

## Puesta en marcha local

Requiere **PHP 8.3 o superior** (el `php` por defecto de algunas máquinas es 8.2: usar el de Homebrew).

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed   # carga el contenido del cliente: familias, medidas, servicios, FAQ, horarios, páginas, fotos y catálogo
npm ci && npm run build
php artisan serve
```

El panel queda en `/{SITIO_ADMIN_PATH}` (por defecto `/panel`).

## Calidad (todo en verde antes de commitear)

```bash
vendor/bin/pint --test
vendor/bin/phpstan analyse --memory-limit=1G     # nivel 5, sin ignores ni baseline
vendor/bin/pest --parallel                        # 517 tests
composer audit && npm audit                       # 0 vulnerabilidades antes de desplegar
```

Producción: `composer install --no-dev --optimize-autoloader` y `npm ci` (respetan los lockfiles). Los assets se
compilan en local y se suben por SCP; nunca se corre Node en el servidor.

## Datos del administrador y del correo

- `SITIO_ADMIN_EMAIL` y `SITIO_ADMIN_PASSWORD` definen el usuario que crea el seeder (sólo en el primer deploy).
- `php artisan usuarios:crear-webmaster` crea la cuenta protegida de mantenimiento (pide la contraseña por pantalla).
- El correo que recibe las cotizaciones se carga en el panel (Configuraciones → Generales). El remitente y el SMTP
  salen de `MAIL_*`. Los avisos van en cola: en el servidor hace falta el cron de `schedule:run` cada minuto.

## Qué hay

Sitio público de 8 páginas más páginas legales dinámicas, catálogo con fichas y medidas, formulario de cotización
(con CI/RUC, adjuntos y anti-spam), panel por secciones con Publicada/Borrador y vista previa, bandeja de
cotizaciones, integraciones con consentimiento (GTM, Meta, Turnstile), SEO (sitemap, JSON-LD, Open Graph) y
respaldos. Detalle en `docs/09`.
