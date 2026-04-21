# Catálogo de componentes UI — SistemasEscolares

Todos los componentes viven en `resources/views/components/ui/`.
Se invocan con el prefijo `<x-ui.*>`.

## Botones

```blade
<x-ui.button variant="primary" icon="plus">Nuevo</x-ui.button>
<x-ui.button variant="secondary">Cancelar</x-ui.button>
<x-ui.button variant="ghost">Ver</x-ui.button>
<x-ui.button variant="danger">Eliminar</x-ui.button>
<x-ui.button variant="link">Ver más →</x-ui.button>

{{-- Tamaños --}}
<x-ui.button size="sm">Pequeño</x-ui.button>
<x-ui.button size="lg">Grande</x-ui.button>

{{-- Con Livewire --}}
<x-ui.button wire:click="guardar" type="submit">Guardar</x-ui.button>
```

Props: `variant`, `size`, `icon` (nombre del componente icon), `type`.

## Campos de formulario

### `<x-ui.input>`
```blade
<x-ui.input
    label="DNI"
    :error="$errors->first('dni')"
    :required="true"
    wire:model.blur="dni"
    placeholder="28345678"
/>
```

### `<x-ui.select>`
```blade
<x-ui.select label="Nivel" wire:model.blur="idnivel" :required="true" :error="$errors->first('idnivel')">
    <option value="">Seleccione…</option>
    @foreach($niveles as $n)
        <option value="{{ $n->id }}">{{ $n->nivel }}</option>
    @endforeach
</x-ui.select>
```

### `<x-ui.textarea>`
```blade
<x-ui.textarea label="Observaciones" wire:model.blur="obs" :rows="3" />
```

### `<x-ui.form-field>` — wrapper genérico
```blade
<x-ui.form-field label="Campo" :error="$errors->first('campo')" required>
    <input type="text" wire:model.blur="campo" class="input" />
</x-ui.form-field>
```

## Estructura de página

### `<x-ui.page-header>`
```blade
<x-ui.page-header
    title="Ciclos lectivos"
    subtitle="Gestión de años lectivos"
    :breadcrumbs="[
        ['label' => 'Inicio', 'href' => route('staff.dashboard')],
        ['label' => 'Ciclos lectivos'],
    ]"
>
    <x-slot:actions>
        <x-ui.button wire:click="crear" icon="plus">Nuevo</x-ui.button>
    </x-slot:actions>
</x-ui.page-header>
```

### `<x-ui.card>`
```blade
<x-ui.card title="Datos institucionales">
    <x-slot:actions>
        <x-ui.button size="sm">Editar</x-ui.button>
    </x-slot:actions>
    Contenido de la tarjeta…
</x-ui.card>
```

### `<x-ui.section>`
```blade
<x-ui.section title="Datos personales" description="Información básica del alumno">
    <div class="grid grid-cols-2 gap-4">…</div>
</x-ui.section>
```

## Tabla

```blade
<x-ui.table>
    <thead>
        <tr>
            <x-ui.th>Nombre</x-ui.th>
            <x-ui.th right>Acciones</x-ui.th>
        </tr>
    </thead>
    <tbody class="divide-y divide-neutral-100">
        @foreach($items as $item)
            <tr class="tr-hover">
                <x-ui.td>{{ $item->nombre }}</x-ui.td>
                <x-ui.td right>
                    <button class="btn-icon" wire:click="editar({{ $item->id }})">
                        <x-icons.pencil class="w-4 h-4" />
                    </button>
                </x-ui.td>
            </tr>
        @endforeach
    </tbody>
</x-ui.table>
```

## Modal

```blade
<div x-data="{ showModal: false }">
    <x-ui.button @click="showModal = true">Abrir modal</x-ui.button>

    <x-ui.modal title="Confirmar acción" x-show="showModal">
        <p>¿Estás seguro?</p>
        <x-slot:footer>
            <x-ui.button variant="ghost" @click="showModal = false">Cancelar</x-ui.button>
            <x-ui.button variant="primary" wire:click="confirmar">Confirmar</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
```

Modal destructivo (fondo rojo en header):
```blade
<x-ui.modal title="Eliminar registro" :danger="true" x-show="showDeleteModal">…</x-ui.modal>
```

## Alertas y badges

```blade
<x-ui.alert variant="success">Guardado correctamente.</x-ui.alert>
<x-ui.alert variant="danger">Error al procesar.</x-ui.alert>
<x-ui.alert variant="warning">Advertencia: esto eliminará datos.</x-ui.alert>
<x-ui.alert variant="info">Información importante.</x-ui.alert>

<x-ui.badge variant="primary">Activo</x-ui.badge>
<x-ui.badge variant="success">Aprobado</x-ui.badge>
<x-ui.badge variant="danger">Inactivo</x-ui.badge>
<x-ui.badge variant="neutral">Sin estado</x-ui.badge>
```

## Toast (notificaciones)

Incluido una sola vez en el layout principal via `<x-ui.toast />`.

Desde un componente Livewire:
```php
$this->dispatch('toast', message: 'Guardado correctamente', type: 'success');
$this->dispatch('toast', message: 'Error al guardar', type: 'danger');
```

## Estados vacíos y skeleton

```blade
<x-ui.empty-state
    title="Sin resultados"
    message="No hay ciclos lectivos registrados."
    icon="calendar"
>
    <x-slot:action>
        <x-ui.button wire:click="crear" icon="plus">Nuevo</x-ui.button>
    </x-slot:action>
</x-ui.empty-state>

{{-- Tabla cargando --}}
<x-ui.skeleton :rows="5" :cols="4" />
```

## Paginación

```blade
<x-ui.pagination :paginator="$items" />
```

## Iconos Heroicons inline

Todos en `resources/views/components/icons/`. Se usan como:

```blade
<x-icons.plus class="w-4 h-4" />
<x-icons.pencil class="w-4 h-4 text-primary-500" />
<x-icons.trash class="w-4 h-4 text-danger-500" />
```

Íconos disponibles: `plus`, `pencil`, `trash`, `check`, `x-mark`, `magnifying-glass`,
`chevron-right`, `chevron-down`, `home`, `exclamation-triangle`, `check-circle`,
`information-circle`, `user`, `users`, `arrow-right-on-rectangle`, `bars-3`,
`academic-cap`, `document-text`, `calendar`, `cog-6-tooth`, `key`,
`lock-closed`, `eye`, `funnel`.
