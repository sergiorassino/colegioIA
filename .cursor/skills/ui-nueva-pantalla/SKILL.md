# Skill: ui-nueva-pantalla

> Checklist paso a paso para crear cualquier pantalla nueva en SistemasEscolares,
> garantizando que cumpla el Design System, los patrones de UX y los estándares de accesibilidad.

## Cuándo usar este skill

Antes de crear o refactorizar una pantalla (componente Livewire + vista Blade). Seguir el checklist en orden.

---

## CHECKLIST COMPLETO

### 1. Determinar el tipo de pantalla

- [ ] **ABM estándar** → componente Livewire + vista inline (sin rutas separadas)
- [ ] **Formulario largo (multi-tab)** → `LegajoForm` como referencia
- [ ] **Solo lectura / listado** → tabla con filtros y paginación
- [ ] **Autogestión alumno** → layout `autogestion`, mobile-first, sin sidebar
- [ ] **Login** → layout `auth`, card centrada

### 2. Componente Livewire (PHP)

- [ ] Namespace: `App\Livewire\Core\` (gestión) o `App\Livewire\Autogestion\` (alumno)
- [ ] `use WithPagination;` si hay listado paginado
- [ ] Propiedades con tipos PHP estrictos (`?int`, `?string`, `bool`)
- [ ] Validaciones con `#[Validate(...)]` en las propiedades
- [ ] Hook `updatedXxx(): void` → `$this->validateOnly('xxx')` en campos de formulario
- [ ] Método `guardar()`: `$this->validate()` → lógica → `$this->dispatch('toast', ...)`
- [ ] Método `cancelar()`: `$this->reset([...])` + `$this->resetValidation()`
- [ ] Confirmación de baja con `$confirmDeleteId` (no `confirm()` de JS)
- [ ] Método `render()` devuelve view con datos mínimos necesarios

### 3. Vista Blade

#### Estructura obligatoria
```
resources/views/livewire/core/{módulo}/index.blade.php
resources/views/livewire/core/{módulo}/form.blade.php  (si aplica)
```

#### Bloques en orden:
- [ ] `<div>` raíz única (requerida por Livewire)
- [ ] `<x-ui.page-header title="..." :breadcrumbs="[...]">` con `<x-slot:actions>` si hay botón primario
- [ ] Flash alerts con `<x-ui.alert variant="success|danger">`
- [ ] Formulario en `<x-ui.card>` con `<x-slot:title>`
- [ ] Tabla con `<x-ui.table>`, `<x-ui.th>`, `<x-ui.td>`, clase `tr-hover` en `<tr>`
- [ ] Estado vacío con `<x-ui.empty-state title="..." message="..." icon="..." />`
- [ ] Confirmación de baja inline (no modal externo para operaciones simples)

### 4. Paleta y tokens — NO romper

- [ ] Fondo de sidebar: `bg-brand-jet`
- [ ] Acción primaria: `bg-primary-600` o clase `.btn-primary`
- [ ] Foco: `ring-primary-500`
- [ ] Sin `indigo-*`, `blue-*`, `red-*`, `gray-*` Tailwind nativos — usar paleta SE
- [ ] Semánticos: `text-success-700 bg-success-50`, `text-danger-700 bg-danger-50`, etc.

### 5. Formularios y validación

- [ ] Labels con `class="label"` y `class="label label-required"` si obligatorio
- [ ] Inputs con `class="input @error('campo') input-error @enderror"`
- [ ] Selects con `class="select @error('campo') input-error @enderror"`
- [ ] Mensajes de error: `<p class="error-msg"><x-icons.exclamation-triangle .../> {{ $message }}</p>`
- [ ] `wire:model.blur` (no `.live` salvo búsquedas con debounce)
- [ ] Botón submit con `wire:loading.attr="disabled"` o componente `<x-ui.button type="submit">`

### 6. Iconos — solo Heroicons inline

- [ ] Usar `<x-icons.nombre class="w-4 h-4" />`
- [ ] Botones solo-icono: `<button class="btn-icon" aria-label="Descripción"><x-icons.pencil .../></button>`
- [ ] Iconos decorativos: `aria-hidden="true"`
- [ ] Si el icono no existe en `resources/views/components/icons/`, crearlo como SVG inline

### 7. Accesibilidad

- [ ] Todo campo de formulario tiene `<label for="id">` o `aria-label`
- [ ] Campos con error: `aria-invalid="true"` + `aria-describedby="campo-error"`
- [ ] Mensaje de error tiene `id="campo-error"` y `role="alert"`
- [ ] Botones de acción sin texto: `aria-label` descriptivo
- [ ] Tablas: `<th scope="col">` en encabezados
- [ ] Modales: `role="dialog" aria-modal="true" aria-labelledby="modal-title"`
- [ ] Navegación: `<nav aria-label="...">` y `<nav aria-label="Ruta de navegación">` para breadcrumbs

### 8. Responsividad

- [ ] Formularios: grid `grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4`
- [ ] Acciones de tabla en `<x-ui.td right>` para alinear a la derecha
- [ ] Tablas con `overflow-x-auto` (ya incluido en `<x-ui.table>`)
- [ ] En autogestión: `max-w-xl mx-auto px-4 pb-24` + bottom nav visible

### 9. Navegación

- [ ] Links internos con `wire:navigate`
- [ ] Breadcrumbs siempre presentes (incluso en pantallas de formulario)
- [ ] Botón "Volver" al index si es pantalla de detalle/form separado

### 10. Tests (obligatorio)

- [ ] Crear `tests/Feature/Livewire/{Módulo}Test.php`
- [ ] Test: renderiza sin error (smoke test)
- [ ] Test: formulario con datos válidos guarda correctamente
- [ ] Test: formulario con datos inválidos muestra errores
- [ ] Test: confirmación de baja elimina el registro
- [ ] Test: usuario sin permiso recibe 403
- [ ] Correr `php artisan test --filter={Módulo}Test` → verde antes de finalizar

---

## Comandos útiles

```bash
# Crear componente Livewire
php artisan make:livewire Core\\NombreIndex

# Correr tests del módulo
php artisan test --filter=NombreTest

# Build frontend
npm run build

# Lint PHP
./vendor/bin/pint

# Análisis estático
./vendor/bin/phpstan analyse --level=6
```

## Referencias

- Design System completo: `docs/design-system.md`
- Componentes UI: `docs/ui-componentes.md`
- Patrones UX: `docs/ux-patterns.md`
- Validación: `docs/validacion-formularios.md`
- Accesibilidad: `docs/accesibilidad.md`
- Ejemplo de referencia: `app/Livewire/Core/Terlec/TerlecIndex.php` + `resources/views/livewire/core/terlec/index.blade.php`
