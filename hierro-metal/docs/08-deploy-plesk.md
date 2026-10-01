# 08 — Deploy en Plesk (mismo procedimiento que Cateura)

> Basado en el deploy de Cateura Accesorios (hosting Plesk de webparaguay). Lo que cambia respecto de
> Cateura está en §2. Los `<marcadores>` son datos que sólo tiene el cliente o el hosting.

## 1. Reglas del procedimiento (se mantienen igual que en Cateura)

1. **Los assets se compilan en local** (`npm ci && npm run build`) y `public/build/` se sube por **SCP**.
   Nunca se corre Node en el servidor.
2. **El código va por git** (`git pull`), nunca por SCP. SCP sólo para `public/build/` y archivos de `storage/`.
3. **Deploy de código = un script fijo en el servidor** (`web/deploy/deploy-hierro.sh`, copiado a una carpeta
   fuera de `httpdocs/`), disparado por una **clave SSH dedicada con `command=` forzado**: aunque se le mande
   cualquier comando, el servidor sólo ejecuta ese script, nunca abre una shell. Revocable borrando una línea
   de `authorized_keys`.
4. **Claude nunca ve ni guarda contraseñas** del servidor, de la base de datos ni del correo: las escribe el
   cliente en el `.env` del servidor.
5. **No se hace `git push` sin que se pida explícitamente en ese momento.**
6. En Plesk el `php` y el `composer` por defecto del shell son viejos: se usan
   `/opt/plesk/php/8.3/bin/php` y `/usr/local/psa/var/modules/composer/composer.phar` (el `composer` de
   `/usr/local/bin` es un wrapper, no el `.phar`).
7. Primero **staging** (subdominio de prueba, indexación bloqueada), después producción. El servidor de
   producción se define cuando llegue ese momento.

## 2. Diferencias con Cateura (importantes)

| Tema | Cateura | Hierro Metal |
|---|---|---|
| **Cron** | Sin cron (se difería a la VPS) | **Obligatorio.** Los avisos de cotización van en cola y se procesan con `schedule:run` (ver §5). Sin cron, el cliente no recibe ningún correo. También corre los reintentos y los respaldos. |
| **Carpeta de la app** | Raíz del repo | La app está en `web/` dentro de un repo que también tiene documentación del proyecto. Document root de Plesk: `httpdocs/public`. |
| **Repositorio** | Público, clon por HTTPS | **Privado** (`git@github.com:Leonshy/hierro-metal-web-insitucional.git`). El servidor usa una *deploy key* de sólo lectura y un clon *sparse* de `web/` (decidido: los documentos internos no van al servidor). |
| **Seeders** | `db:seed` | El contenido se siembra **una sola vez**. Los siguientes deploys **no** corren seeders. |
| **Administrador** | `app:crear-admin` | `AdminUserSeeder` (seguro de repetir: no cambia la contraseña de un admin existente). |
| **Panel** | `/admin` | `/panel` (`SITIO_ADMIN_PATH`). |
| **PHP** | 8.2 / 8.3 | Requiere **8.3**. |

## 3. Antes de cada deploy (en local)

```bash
cd web
composer audit && npm audit            # 0 vulnerabilidades
php artisan test                       # todo en verde
vendor/bin/pint --test
npm ci && npm run build                # 'npm ci', no 'npm install': respeta el lockfile
git status                             # árbol limpio; .env y .env.* NO están trackeados
```

## 4. Primer deploy (una sola vez)

1. En Plesk: crear la suscripción/dominio, **PHP 8.3**, base de datos MySQL, SSL (Let's Encrypt) y poner el
   **document root en `httpdocs/public`** (si queda más arriba, Plesk sirve un 403 y se expondría el `.env`).
2. **Repositorio privado: el servidor sólo recibe la app, directo en `httpdocs/`.** La rama `deploy-web` es el
   contenido de `web/` como raíz (la genera `web/deploy/rama-deploy.sh`) y no incluye los documentos internos
   (plan, legajo, presupuesto). En el servidor se genera una clave, se agrega en GitHub como *deploy key* **de
   sólo lectura** y se clona esa rama:

   ```bash
   ssh-keygen -t ed25519 -N "" -f ~/.ssh/github_hierro -C "hierro-deploy-solo-lectura"
   cat ~/.ssh/github_hierro.pub        # copiar a GitHub → repo → Settings → Deploy keys (SIN permiso de escritura)
   printf 'Host github.com\n  IdentityFile ~/.ssh/github_hierro\n  IdentitiesOnly yes\n' >> ~/.ssh/config
   cd ~/httpdocs && ls -A               # vaciar el placeholder de Plesk si lo hay (verificar antes de borrar)
   git clone --branch deploy-web --single-branch git@github.com:Leonshy/hierro-metal-web-insitucional.git .
   ```

   Document root de Plesk: **`httpdocs/public`**.
3. `composer install --no-dev --optimize-autoloader` (con el binario de Plesk).
4. Crear el `.env` de producción **en el servidor** (lo escribe el cliente, nadie más lo ve):
   `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://…`, `SESSION_SECURE_COOKIE=true`, `DB_*` (MySQL), `MAIL_*`,
   `SITIO_COTIZACIONES_EMAIL`, `SITIO_ADMIN_EMAIL` y `SITIO_ADMIN_PASSWORD`.
   En **staging** además `SITIO_BLOCK_INDEXING=true` (robots.txt y meta noindex); en producción, `false`.
5. `php artisan key:generate`, `php artisan migrate --force`, `php artisan db:seed --force` (sólo esta vez),
   `php artisan storage:link`.
6. **Cuenta de mantenimiento de WebParaguay** (`webmaster@webparaguay.com`): se crea con
   `php artisan usuarios:crear-webmaster` **en la sesión SSH de quien tiene la contraseña**: la pide por
   pantalla sin mostrarla (mínimo 12 caracteres) y no queda en ningún archivo ni en el repositorio. Es una
   cuenta protegida: aparece en la lista de usuarios del panel pero no se puede eliminar, desactivar ni
   cambiarle el correo (ni el administrador del cliente). Es seguro repetir el comando: si existe, no toca nada.
7. Subir `public/build/` por SCP (desde `web/`): ver §6. Verificar con `ls` que quedó en `public/build/` y no anidado.
8. `chmod -R 775 storage bootstrap/cache`.
9. Instalar el script y la clave restringida (§7) y el cron (§5).

## 5. Cron (obligatorio)

En Plesk → Tareas programadas, cada minuto:

```
/opt/plesk/php/8.3/bin/php /var/www/vhosts/<dominio>/httpdocs/artisan schedule:run >> /dev/null 2>&1
```

Procesa la cola de correos (cada minuto), reintenta avisos fallidos (cada 5) y corre los respaldos (de noche).

## 6. Actualizaciones

```bash
# 1) Local: assets, sólo si cambió CSS/JS
cd web && npm ci && npm run build
scp -P <puerto> -r public/build/ <usuario>@<ip>:/var/www/vhosts/<dominio>/httpdocs/public/
#    OJO: si public/build ya existe en el destino, scp -r anida la carpeta. Verificar con ls y, si hace falta,
#    subir el contenido: scp -r public/build/* … public/build/

# 2) Código: en local `web/deploy/rama-deploy.sh` regenera `deploy-web`; el push (`git push origin deploy-web`)
#    se pide explícitamente cada vez; después se dispara el script del servidor
ssh -i ~/.ssh/<clave-de-deploy> -p <puerto> <usuario>@<ip>
```

## 7. Clave de deploy restringida (una vez por servidor)

En el `~/.ssh/authorized_keys` del usuario de Plesk, una línea con la clave pública y las restricciones:

```
command="/var/www/vhosts/<dominio>/deploy-hierro.sh",no-port-forwarding,no-X11-forwarding,no-agent-forwarding,no-pty ssh-ed25519 AAAA… deploy-hierro
```

Verificar que `ssh … whoami` **no** devuelve un usuario sino que corre el script.

## 8. Ajustes de Plesk (nginx)

Plesk puede servir los estáticos con nginx y entonces el `.htaccess` **no se aplica**. Si
`curl -I https://<dominio>/build/assets/<archivo>` no muestra `cache-control: …immutable` ni
`content-encoding`, agregar en *Apache y nginx → Directivas adicionales de nginx*:

```nginx
gzip on;
gzip_types text/css application/javascript application/json application/ld+json image/svg+xml;
location ^~ /build/ { expires 1y; add_header Cache-Control "public, immutable"; }
```

## 9. Verificación posterior

- `https://<dominio>/` responde 200 y `/panel` abre el login.
- Mandar una cotización de prueba, comprobar que llega el correo (cron + `MAIL_*`) y **borrarla** del panel.
- `robots.txt`: staging `Disallow: /`; producción con `Sitemap:`.
- Cabeceras de caché y compresión (§8) y que el `.env` **no** es accesible por web.
- Si las URLs salen en `http://` detrás del proxy, configurar `TrustProxies` o forzar el esquema.
