# Primer despliegue en producción

> Procedimiento para desplegar el nuevo sistema sobre la base de datos MySQL existente en producción, **sin tocar estructuras de tablas legacy**.

## Prerrequisitos

- Acceso SSH o FTP al servidor de hosting
- Acceso a MySQL de producción (para el backup)
- El código está en su versión de release (assets compilados con `npm run build`)

## Paso 1 — Backup completo

```bash
# Backup completo de la BD de producción
mysqldump -u USUARIO -p NOMBRE_BD > backup_prod_$(date +%Y%m%d_%H%M%S).sql
```

Guardar el backup en un lugar seguro fuera del servidor.

## Paso 2 — Subir código y configurar .env

1. Subir el código al servidor (sin `node_modules/`, sin `.env`)
2. Subir `public/build/` (assets compilados localmente con `npm run build`)
3. Copiar `.env.example` a `.env` y configurar:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com

DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nombre_bd_produccion
DB_USERNAME=usuario_mysql
DB_PASSWORD=password_mysql

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

4. Generar app key:
```bash
php artisan key:generate --force
```

## Paso 3 — Instalar dependencias PHP

```bash
composer install --no-dev --optimize-autoloader
```

## Paso 4 — Crear tabla de migraciones

```bash
php artisan migrate:install
```

Esto crea solo la tabla `migrations` en la BD (si no existe). No toca ninguna tabla legacy.

## Paso 5 — Marcar schema inicial como aplicado

El archivo `database/schema/mysql-schema.sql` contiene la foto del schema legacy.
Como la BD de producción ya tiene todas esas tablas, se marca como "ya aplicado" sin ejecutarlo:

```sql
-- Ejecutar directamente en MySQL de producción:
INSERT INTO migrations (migration, batch) VALUES ('0000_00_00_000000_initial_schema', 1);
```

## Paso 6 — Correr solo las migraciones aditivas

```bash
php artisan migrate --force
```

Esto correrá únicamente las migraciones que NO están en la tabla `migrations`:
- `create_sessions_table`
- `create_cache_table`
- `create_jobs_table`
- `create_failed_jobs_table`

**Estas tablas NO existían en producción**, por lo que se crearán sin problema.

## Paso 7 — Smoke tests

Verificar que el sistema funciona correctamente:

1. Login staff: `/login/staff` → ingresar con un DNI/clave existente en `profesores`
2. Listar ciclos lectivos: navegar a `/staff/ciclos` → deben aparecer los ciclos existentes
3. Listar legajos: navegar a `/staff/legajos` → deben aparecer los alumnos existentes
4. Login alumno: `/login/alumno` → ingresar con un DNI/clave existente en `legajos`

## Plan de rollback

Si algo falla, revertir:

```sql
-- Eliminar tablas aditivas creadas (en orden para respetar FKs)
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `migrations`;
```

Restaurar backup si hay corrupción de datos:
```bash
mysql -u USUARIO -p NOMBRE_BD < backup_prod_YYYYMMDD_HHMMSS.sql
```

## Migraciones futuras en producción

Para cada cambio de schema posterior al primer deploy:

```bash
# 1. Hacer backup previo
mysqldump -u USUARIO -p NOMBRE_BD > backup_pre_migration_$(date +%Y%m%d).sql

# 2. Correr la migración
php artisan migrate --force

# 3. Verificar que la migración se aplicó
php artisan migrate:status
```

Ver detalles en [evolucion-schema.md](evolucion-schema.md).
