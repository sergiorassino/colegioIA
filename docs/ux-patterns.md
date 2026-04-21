# Patrones UX obligatorios — SistemasEscolares

## 1. Pantalla de listado (index)

```
┌─ <x-ui.page-header title="…" :breadcrumbs="[…]">
│     <x-slot:actions> <x-ui.button icon="plus">Nuevo</x-ui.button> </x-slot:actions>
├─ Barra de filtros (search input + selects) con wire:model.live.debounce.350ms
├─ <x-ui.table>
│     thead: columnas con <x-ui.th>
│     tbody: filas con <tr class="tr-hover"> + acciones btn-icon
│     empty: <x-ui.empty-state>
├─ <x-ui.pagination :paginator="$items" />  (si aplica)
```

- Usar `#[Url]` en propiedades de filtro para persistir en query string.
- Mostrar spinner mientras carga (`wire:loading`).
- Acciones de fila: ícono-botón (`btn-icon`) para editar, `btn-icon-danger` para borrar.
- Confirmación inline en la fila (sin modal) para bajas simples.

## 2. Formulario (create / edit)

```
┌─ <x-ui.page-header title="Nuevo/Editar" :breadcrumbs="[…]">
│     <x-slot:actions> Guardar + Cancelar </x-slot:actions>
├─ @if ($errors->count() >= 3) resumen de errores
├─ <x-ui.card title="Sección 1">
│     grid de campos con labels + inputs + error-msg
├─ <x-ui.card title="Sección 2"> …
├─ Botones fijos en la parte inferior (sticky bottom)
```

- Orden de campos: identificación → contacto → administrativo.
- `wire:model.blur` en todos los inputs (no `live` salvo búsquedas).
- Llamar `$this->dispatch('scroll-to-error')` si la validación falla.
- El botón Guardar muestra spinner con `wire:loading`.

## 3. Formulario tabbed (legajos)

```
├─ Tabs horizontales (scroll overflow en mobile)
│   wire:click="setTab('...')" → $activeTab
├─ Contenido de la tab activa en <x-ui.card>
├─ Botón Guardar sticky en la parte inferior
```

## 4. Confirmación destructiva (modal)

Usar cuando la acción es irreversible o afecta muchos datos:

- Modal con `danger` prop activada (header rojo).
- Texto explicativo del impacto.
- Input de texto "Escribí BORRAR para confirmar" (opcional, para eliminaciones masivas).
- Botón "Cancelar" a la izquierda, "Eliminar" a la derecha.

Para bajas simples (sin datos asociados), confirmar inline en la fila sin modal.

## 5. Pantallas de login

```
Layout auth (fondo brand.jet)
  → Logo centrado
  → Card blanca con sombra
    → Título de sección
    → Formulario con labels + inputs + errores
    → Botón submit ancho completo
    → Link alternativo (staff/alumno)
```

## 6. Autogestión alumno

```
Layout autogestion:
  → Header dark sticky (logo + nombre alumno)
  → Contenido centrado max-w-xl
  → Bottom navigation (4 íconos)
  → Safe-area iOS en header y bottom nav
```

- Mobile first: diseñar para 380px primero.
- Tarjetas grandes con ícono para accesos rápidos.
- Sin sidebar.

## 7. Estados de datos vacíos

Siempre usar `<x-ui.empty-state>` con:
- `title`: qué no hay
- `message`: por qué / qué hacer
- `icon`: ícono representativo
- `action` slot (opcional): botón de acción primaria

## 8. Toasts

Emitir eventos desde Livewire y dejar que el componente `<x-ui.toast>` los maneje:

```php
// Éxito
$this->dispatch('toast', message: 'Guardado correctamente.', type: 'success');

// Error
$this->dispatch('toast', message: 'No se pudo guardar.', type: 'danger');
```

El toast se auto-descarta en 4 segundos.
