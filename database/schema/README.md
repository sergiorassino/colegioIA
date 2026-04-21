# database/schema/

Esta carpeta contiene el volcado (dump) del schema de MySQL de producción.

## Cómo generar el schema dump

Cuando tengas acceso a la base de datos de producción (o de desarrollo con los datos reales),
ejecuta el siguiente comando desde la raíz del proyecto:

```bash
php artisan schema:dump --database=mysql --path=database/schema/mysql-schema.sql
```

Este archivo es utilizado por Laravel para reemplazar las migraciones cuando se ejecuta
`php artisan migrate --fresh` en entornos de desarrollo/staging, haciendo el proceso mucho más rápido.

## Alternativa: mysqldump

Si no quieres usar Artisan, también puedes generar el dump manualmente:

```bash
mysqldump -u USUARIO -p NOMBRE_BD --no-data --routines > database/schema/mysql-schema.sql
```

## IMPORTANTE

- **NO subir** este archivo al repositorio si contiene datos sensibles.
- Agregar `database/schema/mysql-schema.sql` al `.gitignore` si el schema contiene información confidencial.
- Para testing se usan SQLite in-memory con las migraciones definidas en
  `database/migrations/2026_04_21_155239_create_core_tables_for_testing.php`.
