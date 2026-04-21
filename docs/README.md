# SistemasEscolares — Documentación

Sistema de gestión pedagógica y de cuotas para escuelas de nivel inicial, primario y secundario.

## Stack tecnológico

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Livewire 3 + Alpine.js + Tailwind CSS
- **Base de datos**: MySQL (existente en producción, sin modificaciones estructurales en primer deploy)
- **Testing**: Pest 3 + Larastan + Pint

## Documentación disponible

| Documento | Descripción |
|---|---|
| [arquitectura.md](arquitectura.md) | Arquitectura general del sistema |
| [modelo-datos.md](modelo-datos.md) | Modelo de datos con diagramas Mermaid |
| [convenciones.md](convenciones.md) | Convenciones de código y nomenclatura |
| [roadmap.md](roadmap.md) | Roadmap de desarrollo por etapas |
| [etapa-01-nucleo.md](etapa-01-nucleo.md) | Detalle de la Etapa 1 |
| [deploy-primer-despliegue-produccion.md](deploy-primer-despliegue-produccion.md) | Procedimiento de primer deploy |
| [evolucion-schema.md](evolucion-schema.md) | Cómo hacer migraciones evolutivas |

## Inicio rápido (desarrollo)

```bash
# 1. Clonar el repo y entrar al directorio
git clone ... colegioIA && cd colegioIA

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias Node
npm install

# 4. Copiar .env
cp .env.example .env
php artisan key:generate

# 5. Configurar BD en .env (apuntar al MySQL existente o dev local)
# DB_HOST=127.0.0.1
# DB_DATABASE=ia_demo
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Correr migraciones (carga mysql-schema.sql + aditivas)
php artisan migrate

# 7. Cargar datos de ejemplo
php artisan db:seed

# 8. Compilar assets
npm run dev

# 9. Servir
php artisan serve
```

## Logins de desarrollo

| Rol | URL | Usuario | Clave |
|---|---|---|---|
| Staff (Admin) | `/login/staff` | DNI del admin en `profesores` | `pwrd` del admin |
| Alumno | `/login/alumno` | DNI del alumno en `legajos` | `pwrd` del alumno |
