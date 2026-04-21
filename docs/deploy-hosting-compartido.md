# Despliegue en Hosting PHP Compartido

> Guía para desplegar SistemasEscolares en un hosting compartido (cPanel, Plesk, etc.)
> donde **no hay SSH** y solo se puede subir archivos por FTP/SFTP o el administrador de archivos.

---

## Requisitos del hosting

| Requisito | Mínimo |
|-----------|--------|
| PHP | 8.2+ |
| Extensiones | pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json, curl, zip |
| MySQL | 5.7+ o MariaDB 10.3+ |
| `public_html` accesible como raíz del dominio | ✓ |

---

## Estructura en el servidor

En un hosting compartido la raíz pública del dominio suele ser `public_html/`.
Laravel necesita que **solo** la carpeta `public/` esté expuesta públicamente.

Estructura recomendada:

```
/home/usuario/
├── public_html/          ← apunta al dominio principal
│   ├── index.php         ← copiado desde laravel/public/index.php (modificado)
│   ├── .htaccess         ← copiado desde laravel/public/.htaccess
│   └── build/            ← assets compilados (npm run build)
│       ├── assets/
│       └── manifest.json
└── colegioIA/            ← código Laravel fuera de public_html
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── resources/
    ├── routes/
    ├── storage/
    ├── vendor/
    └── .env
```

---

## Paso a paso

### 1. Compilar assets localmente

```bash
npm run build
```

Esto genera `public/build/` con los assets minificados.

### 2. Preparar los archivos para subir

```
Subir al servidor (a /home/usuario/colegioIA/):
  - todo EXCEPTO: node_modules/, .git/, .env, storage/logs/*.log

Subir a /home/usuario/public_html/:
  - public/.htaccess
  - public/build/ (carpeta completa)
```

### 3. Modificar `public_html/index.php`

Crear `public_html/index.php` con las rutas ajustadas al servidor:

```php
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Maintenance mode check
if (file_exists($maintenance = __DIR__.'/../colegioIA/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../colegioIA/vendor/autoload.php';

$app = require_once __DIR__.'/../colegioIA/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

### 4. Configurar `.env`

Crear `/home/usuario/colegioIA/.env`:

```env
APP_NAME="SistemasEscolares"
APP_ENV=production
APP_KEY=             # generar con: php artisan key:generate --show
APP_DEBUG=false
APP_URL=https://tudominio.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nombre_base_de_datos_produccion
DB_USERNAME=usuario_mysql
DB_PASSWORD=password_mysql

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=mail.tudominio.com
MAIL_PORT=465
MAIL_USERNAME=noreply@tudominio.com
MAIL_PASSWORD=password_correo
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=noreply@tudominio.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Permisos de carpetas

En el administrador de archivos del hosting, dar permisos de escritura (755/775):

```
/home/usuario/colegioIA/storage/
/home/usuario/colegioIA/storage/app/
/home/usuario/colegioIA/storage/framework/
/home/usuario/colegioIA/storage/framework/cache/
/home/usuario/colegioIA/storage/framework/sessions/
/home/usuario/colegioIA/storage/framework/views/
/home/usuario/colegioIA/storage/logs/
/home/usuario/colegioIA/bootstrap/cache/
```

### 6. Primer despliegue sobre BD de producción existente

Dado que la BD de producción **ya existe** y tiene las tablas legacy, NO se deben ejecutar las migraciones de creación de tablas. Solo se necesitan las migraciones de infraestructura de Laravel.

Si el hosting tiene acceso a PHP CLI (SSH limitado o panel de control con terminal):

```bash
# Solo marcar las migraciones de tablas legacy como ejecutadas
php artisan migrate --path=database/migrations/0001_01_01_000000_create_users_table.php
php artisan migrate --path=database/migrations/0001_01_01_000001_create_cache_table.php
php artisan migrate --path=database/migrations/0001_01_01_000002_create_jobs_table.php

# Luego marcar la migración de core como ejecutada (sin correrla)
# Insertar manualmente en la tabla migrations:
```

Si no hay acceso CLI, ejecutar este SQL en phpMyAdmin o el gestor de BD:

```sql
-- Crear tabla de control de migraciones de Laravel
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- Marcar todas las migraciones como ejecutadas (batch 1)
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2026_04_21_155239_create_core_tables_for_testing', 1);

-- Crear tabla de caché
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB;

-- Crear tabla de sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB;
```

> **NOTA:** Si el driver de sesiones es `file` (recomendado para hosting compartido), la tabla `sessions` no es necesaria.

### 7. Optimizar para producción

Si hay acceso CLI:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

Si no hay CLI, se puede crear un script PHP temporal:

```php
<?php
// optimize.php (colocar en public_html/, ejecutar desde el navegador y luego borrar)
$base = __DIR__ . '/../colegioIA';
require $base . '/vendor/autoload.php';
$app = require $base . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->call('config:cache');
$kernel->call('route:cache');
$kernel->call('view:cache');
echo "Optimización completa.";
```

**Borrar este archivo inmediatamente después de ejecutarlo.**

### 8. Cargar datos iniciales (seeders)

Si hay acceso CLI:

```bash
# Solo seeders de datos paramétricos (seguros para producción)
php artisan db:seed --class=NivelesSeeder
php artisan db:seed --class=TerlecSeeder
php artisan db:seed --class=PlanesSeeder
php artisan db:seed --class=CondicionesSeeder
php artisan db:seed --class=ProfesorTipoSeeder
php artisan db:seed --class=PermisosUsuariosSeeder
```

Si la BD ya tiene estos datos del sistema anterior, verificar antes de insertar (los seeders usan `updateOrInsert` para ser idempotentes).

---

## Configuración del dominio (DNS y SSL)

### `.htaccess` en `public_html/`

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## Verificación post-despliegue

Después de desplegar, verificar:

- [ ] La URL principal carga el login de staff (redirección a `/staff/login`)
- [ ] El login de staff funciona con credenciales de la tabla `profesores`
- [ ] El login de alumnos funciona con credenciales de la tabla `legajos`
- [ ] Los módulos de ABM (terlec, niveles, planes) cargan sin errores
- [ ] La consola de errores de PHP no muestra excepciones (`storage/logs/laravel.log`)

---

## Solución de problemas comunes

| Problema | Causa probable | Solución |
|----------|----------------|----------|
| Error 500 | Permisos de `storage/` incorrectos | Verificar que `storage/` y `bootstrap/cache/` tengan permisos 755 |
| "Class not found" | Autoload desactualizado | Ejecutar `composer dump-autoload` o subir la carpeta `vendor/` completa |
| Página en blanco | `APP_DEBUG=false` oculta el error | Revisar `storage/logs/laravel.log` |
| Sesión perdida constantemente | Driver de sesión mal configurado | Usar `SESSION_DRIVER=file` y verificar permisos de `storage/framework/sessions/` |
| Assets CSS no cargan | Rutas de Vite mal configuradas | Verificar `ASSET_URL` en `.env` o el `APP_URL` |
| Login funciona pero redirige al mismo login | Fallo al guardar la sesión | Verificar permisos de `storage/framework/sessions/` |
