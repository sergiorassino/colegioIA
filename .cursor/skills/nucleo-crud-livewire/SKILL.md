# Skill: CRUD Livewire sobre tabla legacy

## Cuándo usar

Al crear un componente Livewire 3 para gestión ABM (Alta/Baja/Modificación) de una entidad del núcleo.

## Checklist

1. El modelo existe con `$timestamps = false` y relaciones correctas
2. El componente va en `app/Livewire/Core/`
3. La vista va en `resources/views/livewire/core/`
4. Usa el layout `layouts.staff`
5. Tiene paginación (`WithPagination`)
6. Tiene validación de dependencias en la baja
7. Tiene confirmación de baja con modal (no `confirm()` de JS)
8. Tiene `wire:loading` en botones
9. Tiene estado vacío en tabla
10. Tiene test con `Livewire::test()`

## Plantilla componente

```php
<?php

namespace App\Livewire\Core;

use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Core\Modelo;

class AbmNombreModelo extends Component
{
    use WithPagination;

    public string $buscar = '';
    public bool $modalAbierto = false;
    public bool $confirmandoBaja = false;
    public ?int $editandoId = null;

    // Campos del formulario
    #[Rule('required|string|max:70')]
    public string $campo1 = '';

    #[Rule('required|integer')]
    public int $campo2 = 0;

    public function updatingBuscar(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $items = Modelo::when($this->buscar, fn($q) => $q->where('campo1', 'like', "%{$this->buscar}%"))
            ->orderBy('campo2')
            ->paginate(15);

        return view('livewire.core.abm-nombre-modelo', compact('items'))
            ->layout('layouts.staff');
    }

    public function nuevo(): void
    {
        $this->resetForm();
        $this->editandoId = null;
        $this->modalAbierto = true;
    }

    public function editar(int $id): void
    {
        $item = Modelo::findOrFail($id);
        $this->editandoId = $id;
        $this->campo1 = $item->campo1;
        $this->campo2 = $item->campo2;
        $this->resetValidation();
        $this->modalAbierto = true;
    }

    public function guardar(): void
    {
        $this->validate();

        if ($this->editandoId) {
            Modelo::findOrFail($this->editandoId)->update([
                'campo1' => $this->campo1,
                'campo2' => $this->campo2,
            ]);
            $this->dispatch('toast', type: 'success', message: 'Actualizado correctamente.');
        } else {
            Modelo::create([
                'campo1' => $this->campo1,
                'campo2' => $this->campo2,
            ]);
            $this->dispatch('toast', type: 'success', message: 'Creado correctamente.');
        }

        $this->modalAbierto = false;
        $this->resetPage();
    }

    public function confirmarBaja(int $id): void
    {
        // Verificar dependencias ANTES de mostrar confirmación
        $dependencias = $this->verificarDependencias($id);
        if ($dependencias) {
            $this->dispatch('toast', type: 'error',
                message: "No se puede eliminar: tiene {$dependencias} dependiente(s).");
            return;
        }
        $this->editandoId = $id;
        $this->confirmandoBaja = true;
    }

    public function eliminar(): void
    {
        Modelo::findOrFail($this->editandoId)->delete();
        $this->dispatch('toast', type: 'success', message: 'Eliminado correctamente.');
        $this->confirmandoBaja = false;
        $this->editandoId = null;
        $this->resetPage();
    }

    private function verificarDependencias(int $id): int
    {
        // Contar registros en tablas hijas
        // return TablaHija::where('idModelo', $id)->count();
        return 0;
    }

    private function resetForm(): void
    {
        $this->campo1 = '';
        $this->campo2 = 0;
        $this->resetValidation();
    }
}
```

## Plantilla vista

```blade
<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Título del módulo</h1>
        <button wire:click="nuevo" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
            + Nuevo
        </button>
    </div>

    {{-- Filtro --}}
    <div class="mb-4">
        <input wire:model.live.debounce.300ms="buscar"
               type="text"
               placeholder="Buscar..."
               class="border-gray-300 rounded-lg shadow-sm w-full max-w-xs" />
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campo 1</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campo 2</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($items as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $item->campo1 }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->campo2 }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button wire:click="editar({{ $item->id }})"
                                        class="text-blue-600 hover:text-blue-800 text-sm">Editar</button>
                                <button wire:click="confirmarBaja({{ $item->id }})"
                                        class="text-red-600 hover:text-red-800 text-sm">Eliminar</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                                No hay registros que mostrar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $items->links() }}
        </div>
    </div>

    {{-- Modal alta/edición --}}
    @if($modalAbierto)
    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold">{{ $editandoId ? 'Editar' : 'Nuevo' }}</h2>
                <button wire:click="$set('modalAbierto', false)" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Campo 1</label>
                    <input wire:model="campo1" type="text" class="border-gray-300 rounded-lg w-full" />
                    @error('campo1') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                <button wire:click="$set('modalAbierto', false)" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Cancelar</button>
                <button wire:click="guardar" wire:loading.attr="disabled" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                    <span wire:loading.remove>Guardar</span>
                    <span wire:loading>Guardando...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal confirmación de baja --}}
    @if($confirmandoBaja)
    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-sm">
            <div class="px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">¿Confirmar eliminación?</h2>
                <p class="text-sm text-gray-600 mt-2">Esta acción no se puede deshacer.</p>
            </div>
            <div class="px-6 py-4 flex justify-end gap-3">
                <button wire:click="$set('confirmandoBaja', false)" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Cancelar</button>
                <button wire:click="eliminar" class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700">Eliminar</button>
            </div>
        </div>
    </div>
    @endif
</div>
```
