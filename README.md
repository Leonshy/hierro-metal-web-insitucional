# Hierro Metal S.R.L. — web

Sitio institucional y de captación de cotizaciones. Laravel 13, PHP 8.3+, Filament 5 (panel), Livewire,
Alpine, Tailwind v4 y Vite. Derivado del panel de Dante (fork limpio, `docs/adr/0001`).

Contexto y reglas del proyecto: `../hierro-metal/CLAUDE.md`. Plan por fases: `../hierro-metal/PLAN.md`.

## Puesta en marcha local

Requiere **PHP 8.3 o superior** (el PHP por defecto de algunas máquinas es 8.2).

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed   # carga el contenido del cliente: familias, medidas, servicios, FAQ, horarios…
npm ci && npm run build
php artisan serve
```

El panel queda en `/{SITIO_ADMIN_PATH}` (por defecto `/panel`).

## Pruebas y seguridad

```bash
php artisan test            # Pest
composer audit && npm audit # deben dar 0 vulnerabilidades antes de desplegar
```

Producción: `composer install --no-dev` y `npm ci` (respetan los lockfiles).

## Datos del administrador y del correo

- `SITIO_ADMIN_EMAIL` y `SITIO_ADMIN_PASSWORD` definen el usuario administrador que crea el seeder.
- El correo de avisos de cotización se carga en el panel (Configuración). El remitente y el SMTP salen de `MAIL_*`.

## Estado (Fase 3)

Base importada y podada: se quitó todo lo específico del colegio (comunicados, calendario, galerías,
documentos, sedes, pre-inscripción, buscador, migración de WordPress). Noticias queda apagado (código
conservado, oculto del menú). Módulos de dominio hechos y sembrados: familias y líneas con medidas, servicios, FAQ,
horarios, rubros, vendedores y cotizaciones (`../hierro-metal/docs/ux-flows/planilla-campos.md`).
