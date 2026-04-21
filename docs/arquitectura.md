# Arquitectura — SistemasEscolares

## Resumen

Monolito PHP (Laravel 11) con frontend renderizado en servidor (Livewire 3).
Sin Node.js en producción: los assets se compilan localmente y se suben pre-compilados.

## Capas

```
┌─────────────────────────────────────────────────────────────┐
│  Browser (mobile-first autogestión / desktop gestión)       │
└─────────────────────┬───────────────────────────────────────┘
                      │ HTTP / WebSocket (Livewire wire:navigate)
┌─────────────────────▼───────────────────────────────────────┐
│  Laravel 11                                                 │
│  ├── Routes (web.php)                                       │
│  │    ├── /login/staff      → StaffLoginForm (Livewire)    │
│  │    ├── /login/alumno     → AlumnoLoginForm (Livewire)   │
│  │    ├── /staff/*          → CRUDs gestión (Livewire)     │
│  │    └── /alumno/*         → Autogestión (Livewire)       │
│  ├── Middleware                                             │
│  │    ├── EnsureStaffAuth   (guard 'staff')                │
│  │    ├── EnsureAlumnoAuth  (guard 'alumno')               │
│  │    ├── EnsurePermiso     (bit en profesores.permisos)   │
│  │    ├── InjectStaffContext (nivel_id, terlec_id → sesión)│
│  │    └── InjectAutogestionContext                         │
│  ├── Livewire Components                                    │
│  │    ├── Auth/  (StaffLoginForm, AlumnoLoginForm)         │
│  │    └── Core/ (ABMs: Ciclos, Niveles, Planes, Cursos...) │
│  ├── Services                                               │
│  │    ├── GenerarCursoService (transaccional)              │
│  │    ├── MatricularAlumnoService (transaccional)          │
│  │    ├── MateriaIntegridadService                         │
│  │    ├── CalificacionesAdapter                            │
│  │    └── EntornoService                                   │
│  ├── Models                                                 │
│  │    ├── Core/  (Terlec, Nivel, Plan, CurPlan, MatPlan,   │
│  │    │           Curso, Materia, Legajo, Matricula,        │
│  │    │           Calificacion, Ento, Condicion, Familia)   │
│  │    └── Auth/  (Profesor, LegajoAuth, ProfesorTipo,      │
│  │                PermisoUsuario)                           │
│  └── Auth/                                                  │
│       ├── LegacyUserProvider (validación híbrida pwrd)     │
│       ├── PermisosService + PermisoSlugMap                 │
│       ├── MenuResolver                                      │
│       ├── ContextoStaff                                     │
│       └── ContextoAutogestion                               │
└─────────────────────┬───────────────────────────────────────┘
                      │ Eloquent ORM
┌─────────────────────▼───────────────────────────────────────┐
│  MySQL (base de datos existente en producción)              │
│  Schema legacy: profesores, legajos, cursos, materias...    │
│  Tablas aditivas: sessions, cache, jobs, failed_jobs        │
└─────────────────────────────────────────────────────────────┘
```

## Autenticación

Dos flujos completamente separados:

### Staff (`/login/staff`)
- Formulario: DNI + clave + nivel + ciclo lectivo
- Validación contra tabla `profesores` (campo `pwrd`, híbrido texto plano / bcrypt)
- Guard: `staff`, provider: `profesores`
- Menú determinado por `profesores.IdTipoProf` (rol) y `profesores.permisos` (bits 0-49)
- Sesión: `profesor_id`, `nivel_id`, `terlec_id`

### Alumnos/Familias (`/login/alumno`)
- Formulario: DNI + clave
- Validación contra tabla `legajos` (campo `pwrd`, híbrido)
- Guard: `alumno`, provider: `legajos`
- Nivel: `legajos.idnivel`
- Ciclo autogestión: `ento.idTerlecVerNotas` del nivel del alumno

## Estrategia de base de datos

Ver [evolucion-schema.md](evolucion-schema.md) para detalles completos.

- **Schema legacy**: snapshotado en `database/schema/mysql-schema.sql` con `php artisan schema:dump`.
- **Migraciones**: solo aditivas (sessions, cache, jobs, failed_jobs) + evolutivas futuras.
- **Producción**: primer deploy sin tocar estructura legacy (ver [deploy-primer-despliegue-produccion.md](deploy-primer-despliegue-produccion.md)).

## Layouts

| Layout | Archivo | Uso |
|---|---|---|
| Auth | `layouts/auth.blade.php` | Páginas de login |
| Staff | `layouts/staff.blade.php` | Gestión (sidebar + topbar, desktop-first) |
| Autogestión | `layouts/autogestion.blade.php` | Alumnos/familias (mobile-first) |
