# Roadmap — SistemasEscolares

## Estado actual

```
Etapa 1 [EN DESARROLLO] ████████░░░░░░░░░░░░ 40%
Etapa 2 [PENDIENTE]     ░░░░░░░░░░░░░░░░░░░░  0%
Etapa 3 [PENDIENTE]     ░░░░░░░░░░░░░░░░░░░░  0%
```

## Etapa 1 — Núcleo (Parametrización + Alumnos/Matrícula)

**Objetivo**: Sistema funcional con autenticación, gestión de parámetros, alumnos y matrículas.

### Módulos incluidos

- [x] Documentación y reglas de Cursor
- [ ] Instalación de Laravel 11 + stack completo
- [ ] Layout staff (sidebar + topbar) y autogestión (mobile-first)
- [ ] Autenticación staff (`/login/staff`): DNI + clave + nivel + ciclo
- [ ] Autenticación alumno (`/login/alumno`): DNI + clave
- [ ] Sistema de permisos basado en `profesores.permisos`
- [ ] ABM Ciclos lectivos (`terlec`)
- [ ] ABM Niveles (`niveles`)
- [ ] ABM Planes (`planes`)
- [ ] ABM Cursos modelo + Materias modelo (`curplan` + `matplan`)
- [ ] ABM Entorno institucional (`ento`)
- [ ] Creación de cursos del año (`cursos` + replicación de `materias`)
- [ ] Edición de materias del año (con integridad `idMatPlan`)
- [ ] ABM Legajos de alumnos
- [ ] Matriculación de alumnos
- [ ] Dashboard de autogestión alumno (mínimo viable)
- [ ] CalificacionesAdapter (Inicial/Primario/Secundario)
- [ ] Tests completos (Auth, Permisos, ABMs, Servicios, Autogestión)
- [ ] CI local (composer test / composer check)
- [ ] Documentación de deploy

## Etapa 2 — Calificaciones y Libro de Temas (Previsto)

- Carga de calificaciones por docente (interfaz según nivel)
- Libro de temas
- Planificaciones y programas
- Portal autogestión: ver calificaciones
- Informes de calificaciones por alumno y curso

## Etapa 3 — Cuotas y Administración (Previsto)

- Gestión de cuotas y becas
- Integración con Siro
- Caja y pagos
- Informes administrativos

## Etapa 4 — Funcionalidades avanzadas (Previsto)

- Gestión de inasistencias
- Sanciones
- Certificados y analíticos
- Comunicaciones (emails masivos)
- Autogestión avanzada (matrícula web, cuotas online)

## Principios de desarrollo

1. **No tocar estructuras de producción**: todo cambio de schema via migraciones nuevas
2. **Tests primero**: ningún módulo sin tests verdes
3. **Expand-contract**: para cambios riesgosos en tablas legacy con datos
4. **Mobile-first**: autogestión diseñada para celular, gestión para desktop
