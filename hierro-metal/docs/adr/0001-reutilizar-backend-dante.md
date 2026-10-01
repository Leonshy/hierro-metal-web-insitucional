# ADR 0001 — Reutilizar el backend y el panel de Dante

**Estado:** **aceptado** (octubre 2026) — inventario hecho y suite verificada: 215 de 219 pruebas pasan en un clon limpio (`docs/03` §1)
**Fecha:** octubre 2026

## Contexto

Hierro Metal necesita un panel autoadministrable con auth segura, medios, SEO, redirecciones,
auditoría y formularios. Dante (web institucional de webparaguay, Laravel, bilingüe ES/IT, panel Filament 5) ya
tiene todo eso construido y probado. (La afirmación de que ya se reutilizó para Grupo GEN no se verificó: ese repo es Laravel 12 sin Filament.)

## Decisión

Arrancar Hierro Metal como **fork limpio del repo de Dante**:

1. Copiar el repo sin historial de contenido ni `.env`, renombrar la app.
2. Vaciar seeders y medios de Dante.
3. Apagar (no borrar) multiidioma y noticias.
4. Agregar los módulos de dominio de `docs/03` §3.

Alternativa considerada: Laravel nuevo + copiar módulos de Dante uno por uno. Se descarta salvo
que la Fase 0 encuentre que Dante tiene demasiado acoplamiento a su contenido.

## Consecuencias

- La Fase 3 baja a 24–34 h (contra 48–64 h de Dante).
- Las mejoras que se hagan acá al panel (p. ej. bandeja de cotizaciones) se documentan para
  llevarlas de vuelta al patrón institucional.
- Riesgo: arrastrar deuda de Dante. Mitigación: el inventario de la Fase 0 lista lo que se
  arrastra a conciencia.

## Verificación en Fase 0

Dante no está acoplado a su contenido de forma que obligue al fallback: modelos, policies y panel
son por módulo. Lo que sobra (Posts, Announcements, CalendarEvents, Galleries, Documents,
Locations, bloques de sede/comunicados) se oculta o se quita. Con eso, **fork limpio sigue siendo
la opción recomendada**. Condición para pasar a "aceptado": clonar, levantar y correr la suite de
Pest en verde. **Cumplida**: 215 de 219 en verde; los 4 restantes son el mapa de redirecciones de WordPress, que no aplica.

**Condiciones del fork** (de `docs/03` §1): usar PHP 8.3, completar `BACKUP_NOTIFICATION_EMAIL`, versionar `.gitkeep` en las carpetas que el código necesita,
actualizar `laravel/framework` y `league/commonmark` (3 avisos de seguridad en `composer audit`), forzar 2FA, y un servicio propio para los adjuntos de cotización.

## Ejecución del fork (Fase 3, octubre 2026)

- **Dónde:** `web/`, dentro de este repo. Se copiaron **sólo los archivos versionados** de Dante (commit `e47bcef`, `git archive`), sin
  vendor, medios ni reportes.
- **Renombrado:** `config/dante.php` pasó a `config/sitio.php`; `DANTE_*` a `SITIO_*`; cookies, eventos y clases de JS con prefijo `sitio`.
- **Retirado** (no aplica a Hierro Metal): migración de WordPress y su mapa de redirecciones, comunicados, calendario, galerías,
  documentos, sedes, pre-inscripción, buscador interno, selector de idioma, ajustes del inicio, bloques de página del colegio y las
  pruebas de todo eso.
- **Apagado, no borrado** (según este ADR): **Noticias** (`Post`, `Category` y sus recursos siguen; sin menú ni rutas públicas) y el
  **segundo idioma** (`sitio.locales = ['es']`; agregar `'it'` lo reactiva).
- **Línea base:** 135 de 135 pruebas en verde. `composer audit` y `npm audit` en cero (se actualizaron `laravel/framework` 13.34 y
  `league/commonmark` 2.10.3).
- **Pendiente del fork:** forzar 2FA (Fase 8), servicio propio de adjuntos de cotización, módulos de dominio (`docs/03` §3).
