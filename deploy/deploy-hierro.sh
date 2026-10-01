#!/bin/bash
# Script de deploy de Hierro Metal — se instala EN EL SERVIDOR, fuera de httpdocs/ (no accesible por web).
# Es el único comando que la clave SSH de deploy puede ejecutar (`command=` forzado en authorized_keys).
# Mismo esquema que Cateura (docs/08-deploy-plesk.md). Reemplazá los <marcadores> al instalarlo.
#
# NO compila assets (npm run build se hace en local y se sube public/build por SCP) y NO corre seeders:
# el contenido se siembra una sola vez en el primer deploy.
set -euo pipefail

PHP=/opt/plesk/php/8.3/bin/php
COMPOSER_PHAR=/usr/local/psa/var/modules/composer/composer.phar
RAIZ=/var/www/vhosts/<dominio>/httpdocs        # raíz del clon del repositorio
APP="$RAIZ/<carpeta-de-la-app>"                # carpeta con artisan (en el repo: web/)

cd "$RAIZ"
git pull --ff-only          # si el servidor tiene cambios locales o la historia divergió, falla en vez de mezclar

cd "$APP"
$PHP "$COMPOSER_PHAR" install --no-dev --optimize-autoloader --no-interaction
$PHP artisan migrate --force
$PHP artisan storage:link 2>/dev/null || true     # idempotente: ya existe después del primer deploy
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache
$PHP artisan queue:restart                         # los procesos del cron toman el código nuevo

echo "Deploy listo: $(git -C "$RAIZ" log --oneline -1)"
