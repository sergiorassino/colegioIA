# Etapa 1 — Núcleo del Sistema

## Alcance

### Autenticación

- `/login/staff`: DNI + clave + nivel + ciclo lectivo
  - Rol `Profesor/a` → menú limitado (Mis cursos, Libro de temas, Calificaciones, Mis datos)
  - Resto de roles → menú completo filtrado por `profesores.permisos`
  - Rol "Sin Rol" → pantalla "contacte al administrador"
- `/login/alumno`: DNI + clave
  - Nivel: desde `legajos.idnivel`
  - Ciclo autogestión: `ento.idTerlecVerNotas` del nivel del alumno
- Listener `PasswordShouldBeRehashed` activo (migración progresiva de plain text → bcrypt)

### Pantallas de gestión

#### ABM Ciclos lectivos (`terlec`)
- Alta, baja, edición de `ano` y `orden`
- Marcar activo (menor `orden`)
- Validación: no borrar si hay dependencias (`cursos`, `matricula`, `calificaciones`, `ento.idTerlecVerNotas*`)

#### ABM Niveles (`niveles`)
- Alta, baja, edición de `nivel` y `abrev`
- Los 5 niveles iniciales vienen por seeder
- Validación: no borrar si tiene `planes`, `ento`, `legajos`, `matricula`, etc. dependientes

#### ABM Entorno institucional (`ento`)
- Un registro por nivel; formulario seccionado:
  - Datos institucionales (insti, cue, ee, cuit, categoría, dirección, etc.)
  - Ciclos (idTerlecVerNotas, idTerlecVerNotas2, idAspiTerlec)
  - Flags de bloqueo (platOff, cargaNotasOff, verNotasOff, matriculaWebOff, arancelesOff) + mensajes
  - Documentos (documAcept1..4)
  - Integraciones (apiDrive, siroIniPrim, siroSecu, siroMje, environment, claveCole)

#### ABM Planes (`planes`)
- Alta, baja, edición por nivel
- Validación: no borrar si tiene `curplan` dependientes

#### ABM Cursos modelo + Materias modelo (`curplan` + `matplan`)
- Una sola pantalla: lista de cursos modelo por plan
- Al abrir un curso modelo: editar `curPlanCurso` + grilla inline de `matplan`
  - Alta, baja, edición de materias modelo (matPlanMateria, ord, abrev, codGE*)
  - Drag-and-drop para reordenar por `ord`
- Validación: no borrar `curplan` si hay `cursos` del año referenciando; no borrar `matplan` si hay `materias` del año

#### Cursos del año (`cursos` + `materias`)
- Listado filtrado por ciclo lectivo y nivel de sesión
- **Crear curso**: elegir `curplan` + datos propios → `GenerarCursoService` (transacción: curso + replica de matplan→materias)
- **Editar curso**: editar datos del curso y materias del año con `MateriaIntegridadService`

**Regla de integridad** (ver `.cursor/rules/070-integridad-curso-materia.mdc`):
- No cambiar ni nullear `idMatPlan` de una materia con vínculo existente
- Renombrar: opción "aplicar también a matplan" (transacción)
- Agregar: opción "agregar también a matplan" (predeterminado) o "materia extraordinaria del año"
- Quitar: quita de `materias`; opción de quitar también de `matplan` solo si no hay otras referencias

#### Legajos de alumnos
- Listado con búsqueda por apellido, DNI, legajo, nivel
- Formulario seccionado: Personal, Madre, Padre, Tutor, Responsable admi, Reglamento
- Alta y edición NO tocan `matricula`

#### Matriculación (`matricula` + `calificaciones`)
- Seleccionar alumno (buscador apellido/DNI/nivel)
- Seleccionar curso del año actual
- Completar datos: condición (REGULAR por defecto), nroMatricula, fechaMatricula, obsMatr
- `MatricularAlumnoService` (transacción):
  1. Crea registro en `matricula`
  2. Por cada `materia` del curso → crea registro en `calificaciones` con todos los IDs
- Validación: no matricular el mismo alumno dos veces en mismo `idTerlec` + `idNivel`

### Autogestión alumno (mínimo viable)
- Dashboard: nivel del alumno, ciclo de autogestión (de `ento.idTerlecVerNotas`), mensajes de flags `*Off`
- Layout mobile-first (drawer)
- El resto de funciones (calificaciones, cuotas) es Etapa 2+

## Servicios clave

| Servicio | Responsabilidad |
|---|---|
| `GenerarCursoService` | Crear un curso del año replicando matplan→materias (atómico) |
| `MatricularAlumnoService` | Crear matrícula + calificaciones vacías por cada materia (atómico) |
| `MateriaIntegridadService` | Editar materias del año protegiendo idMatPlan |
| `CalificacionesAdapter` | Traducir ic01..ic40/tm1..tm6/dic/feb a campos de dominio por nivel |
| `EntornoService` | Exponer contexto institucional, ciclo autogestión y flags de bloqueo |

## Testing (Definition of Done)

Un módulo está terminado cuando:
1. Todos los tests del módulo están verdes
2. `composer check` pasa (pint --test + phpstan + pest)
3. El código sigue las convenciones de `.cursor/rules/`

Ver detalles en `docs/convenciones.md` y `.cursor/rules/040-testing.mdc`.
