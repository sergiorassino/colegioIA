# AGENTS.md — SistemasEscolares

> Guía para agentes de IA (Cursor) que trabajan sobre este proyecto.

## Contexto del proyecto

Sistema de gestión pedagógica y de cuotas para escuelas de nivel inicial, primario y secundario.
Backend Laravel 11, frontend Livewire 3 + Alpine.js + Tailwind CSS.
Base de datos MySQL **existente en producción** — **NO modificar estructuras sin una migración nueva**.

## Reglas obligatorias (siempre activas)

Antes de hacer cualquier cambio, revisar las reglas en `.cursor/rules/`:

| Archivo | Aplica a |
|---|---|
| `000-project-overview.mdc` | Siempre |
| `010-db-schema-existente.mdc` | Siempre al tocar modelos/migraciones |
| `020-laravel-conventions.mdc` | `app/**` |
| `030-livewire-ui.mdc` | `app/Livewire/**`, `resources/views/**` |
| `031-design-system.mdc` | Siempre al crear/editar vistas Blade |
| `032-navegacion-layouts.mdc` | Pantallas nuevas, links de navegación |
| `033-accesibilidad.mdc` | Formularios, tablas, modales, botones-icono |
| `035-validacion-formularios.mdc` | Siempre en formularios Livewire |
| `040-testing.mdc` | `tests/**`, `app/**` (cuando hay cambios) |
| `050-calificaciones-multiproposito.mdc` | Siempre al tocar `calificaciones` |
| `060-seguridad-auth.mdc` | Siempre |
| `070-integridad-curso-materia.mdc` | Siempre al tocar `cursos`/`materias`/`matplan` |

## Skills disponibles

| Skill | Cuándo usarlo |
|---|---|
| `nucleo-modelo-tabla` | Crear un modelo Eloquent sobre tabla legacy |
| `nucleo-crud-livewire` | Plantilla de componente CRUD Livewire |
| `nucleo-generar-curso` | Flujo de alta de curso del año + edición de materias |
| `nucleo-matricular-alumno` | Flujo de matriculación (alumno → matrícula + calificaciones) |
| `auth-legacy-login` | Configurar guards/providers y LoginForm Livewire |
| `auth-permisos-staff` | Chequear y agregar nuevos permisos |
| `ui-nueva-pantalla` | Checklist al crear cualquier pantalla nueva (design system) |

## Flujo de trabajo

1. Leer el plan en `.cursor/plans/núcleo_sistemasescolares_etapa_1_*.plan.md`
2. Consultar `docs/` para contexto de arquitectura y modelo de datos
3. Implementar la feature con los skills correspondientes
4. Escribir tests (obligatorio — ver `040-testing.mdc`)
5. Correr `composer check` antes de marcar como completado

## Prohibiciones críticas

- ❌ Crear tabla `users` de Laravel
- ❌ Instalar `spatie/laravel-permission`
- ❌ Editar `database/schema/mysql-schema.sql` a mano
- ❌ Correr `migrate:fresh`, `migrate:rollback` o `migrate:refresh` en producción
- ❌ Escribir `pwrd` en texto plano desde código nuevo
- ❌ Cambiar `idMatPlan` de una materia existente sin pasar por `MateriaIntegridadService`
- ❌ Commitear un módulo sin sus tests verdes
- ❌ Usar clases Tailwind `indigo-*`, `blue-*`, `red-*`, `gray-*` directas — usar paleta SE
- ❌ Importar Google Fonts ni librerías de iconos externas (Lucide, FontAwesome)
- ❌ Crear pantallas sin `<x-ui.page-header>` y breadcrumbs
- ❌ Agregar `Alpine.start()` manualmente (Livewire 3 lo gestiona)

## Estructura del proyecto

```
app/
  Models/Core/      → Modelos Eloquent del núcleo
  Models/Auth/      → Profesor (Authenticatable), LegajoAuth
  Auth/             → LegacyUserProvider, PermisosService, MenuResolver, ContextoStaff
  Http/Middleware/  → EnsureStaffAuth, EnsureAlumnoAuth, EnsurePermiso
  Livewire/Auth/    → StaffLoginForm, AlumnoLoginForm
  Livewire/Core/    → CRUDs del núcleo
  Services/         → GenerarCursoService, MatricularAlumnoService, etc.
database/
  schema/mysql-schema.sql   → Foto del schema legacy (NO editar a mano)
  migrations/               → Solo migraciones aditivas/evolutivas
docs/                       → Documentación técnica
.cursor/rules/              → Reglas para agentes
.cursor/skills/             → Skills especializados
```
