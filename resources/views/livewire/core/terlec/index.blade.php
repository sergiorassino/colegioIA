<div>
    <x-ui.page-header
        title="Ciclos lectivos"
        subtitle="Gestión de años lectivos del sistema"
        :breadcrumbs="[['label' => 'Inicio', 'href' => route('staff.dashboard')], ['label' => 'Ciclos lectivos']]"
    >
        <x-slot:actions>
            @if(!$showForm)
                <x-ui.button wire:click="crear" icon="plus">Nuevo ciclo</x-ui.button>
            @endif
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Flash --}}
    @if(session('success'))
        <x-ui.alert variant="success" class="mb-4">{{ session('success') }}</x-ui.alert>
    @endif
    @if(session('error'))
        <x-ui.alert variant="danger" class="mb-4">{{ session('error') }}</x-ui.alert>
    @endif

    {{-- Formulario en card --}}
    @if($showForm)
        <x-ui.card class="mb-6">
            <x-slot:title>{{ $editId ? 'Editar ciclo lectivo' : 'Nuevo ciclo lectivo' }}</x-slot:title>

            <form wire:submit="guardar" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="label label-required">Año</label>
                    <input type="number" wire:model.blur="ano" min="1990" max="2100"
                           class="input @error('ano') input-error @enderror"
                           placeholder="2025"
                           @error('ano') aria-invalid="true" @enderror />
                    @error('ano') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label label-required">Orden <span class="text-neutral-400 font-normal">(1 = más reciente)</span></label>
                    <input type="number" wire:model.blur="orden" min="0"
                           class="input @error('orden') input-error @enderror"
                           @error('orden') aria-invalid="true" @enderror />
                    @error('orden') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2 flex items-center justify-end gap-3 pt-2 border-t border-neutral-100">
                    <x-ui.button type="button" variant="ghost" wire:click="cancelar">Cancelar</x-ui.button>
                    <x-ui.button type="submit" variant="primary" icon="check">Guardar</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    @endif

    {{-- Tabla --}}
    <x-ui.table>
        <thead>
            <tr>
                <x-ui.th>Año</x-ui.th>
                <x-ui.th>Orden</x-ui.th>
                <x-ui.th right>Acciones</x-ui.th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100">
            @forelse($this->terlecs as $terlec)
                <tr class="tr-hover">
                    <x-ui.td>
                        <span class="font-semibold text-brand-jet">{{ $terlec->ano }}</span>
                    </x-ui.td>
                    <x-ui.td>{{ $terlec->orden }}</x-ui.td>
                    <x-ui.td right>
                        @if($confirmDeleteId == $terlec->id)
                            <div class="flex items-center justify-end gap-2">
                                <span class="text-xs text-danger-600 font-medium">¿Confirmar borrado?</span>
                                <x-ui.button size="sm" variant="danger" wire:click="borrar">Sí, borrar</x-ui.button>
                                <x-ui.button size="sm" variant="ghost" wire:click="cancelar">No</x-ui.button>
                            </div>
                        @else
                            <div class="flex items-center justify-end gap-1">
                                <button class="btn-icon" wire:click="editar({{ $terlec->id }})" title="Editar">
                                    <x-icons.pencil class="w-4 h-4" />
                                </button>
                                <button class="btn-icon-danger" wire:click="confirmarBorrar({{ $terlec->id }})" title="Borrar">
                                    <x-icons.trash class="w-4 h-4" />
                                </button>
                            </div>
                        @endif
                    </x-ui.td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="p-0">
                        <x-ui.empty-state
                            title="Sin ciclos lectivos"
                            message="Aún no hay ciclos lectivos registrados. Hacé clic en «Nuevo ciclo» para agregar uno."
                            icon="calendar"
                        />
                    </td>
                </tr>
            @endforelse
        </tbody>
    </x-ui.table>
</div>
