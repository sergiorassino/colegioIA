<div>
    <x-ui.page-header
        title="Niveles"
        subtitle="Niveles educativos del sistema"
        :breadcrumbs="[['label' => 'Inicio', 'href' => route('staff.dashboard')], ['label' => 'Niveles']]"
    >
        <x-slot:actions>
            @if(!$showForm)
                <x-ui.button wire:click="crear" icon="plus">Nuevo nivel</x-ui.button>
            @endif
        </x-slot:actions>
    </x-ui.page-header>

    @if(session('success'))
        <x-ui.alert variant="success" class="mb-4">{{ session('success') }}</x-ui.alert>
    @endif
    @if(session('error'))
        <x-ui.alert variant="danger" class="mb-4">{{ session('error') }}</x-ui.alert>
    @endif

    @if($showForm)
        <x-ui.card class="mb-6">
            <x-slot:title>{{ $editId ? 'Editar nivel' : 'Nuevo nivel' }}</x-slot:title>
            <form wire:submit="guardar" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="label label-required">Nombre del nivel</label>
                    <input type="text" wire:model.blur="nivel" maxlength="60"
                           class="input @error('nivel') input-error @enderror" placeholder="Ej: Secundario"
                           @error('nivel') aria-invalid="true" @enderror />
                    @error('nivel') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Abreviatura <span class="text-neutral-400 font-normal">(hasta 5 car.)</span></label>
                    <input type="text" wire:model.blur="abrev" maxlength="5"
                           class="input @error('abrev') input-error @enderror" placeholder="Sec"
                           @error('abrev') aria-invalid="true" @enderror />
                    @error('abrev') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2 flex items-center justify-end gap-3 pt-2 border-t border-neutral-100">
                    <x-ui.button type="button" variant="ghost" wire:click="cancelar">Cancelar</x-ui.button>
                    <x-ui.button type="submit" variant="primary" icon="check">Guardar</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    @endif

    <x-ui.table>
        <thead>
            <tr>
                <x-ui.th>Nombre</x-ui.th>
                <x-ui.th>Abrev.</x-ui.th>
                <x-ui.th right>Acciones</x-ui.th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100">
            @forelse($this->niveles as $nivel)
                <tr class="tr-hover">
                    <x-ui.td><span class="font-medium text-brand-jet">{{ $nivel->nivel }}</span></x-ui.td>
                    <x-ui.td><x-ui.badge variant="neutral">{{ $nivel->abrev }}</x-ui.badge></x-ui.td>
                    <x-ui.td right>
                        @if($confirmDeleteId == $nivel->id)
                            <div class="flex items-center justify-end gap-2">
                                <span class="text-xs text-danger-600 font-medium">¿Confirmar borrado?</span>
                                <x-ui.button size="sm" variant="danger" wire:click="borrar">Sí</x-ui.button>
                                <x-ui.button size="sm" variant="ghost" wire:click="cancelar">No</x-ui.button>
                            </div>
                        @else
                            <div class="flex items-center justify-end gap-1">
                                <button class="btn-icon" wire:click="editar({{ $nivel->id }})" title="Editar">
                                    <x-icons.pencil class="w-4 h-4" />
                                </button>
                                <button class="btn-icon-danger" wire:click="confirmarBorrar({{ $nivel->id }})" title="Borrar">
                                    <x-icons.trash class="w-4 h-4" />
                                </button>
                            </div>
                        @endif
                    </x-ui.td>
                </tr>
            @empty
                <tr><td colspan="3" class="p-0">
                    <x-ui.empty-state title="Sin niveles" message="No hay niveles registrados aún." icon="academic-cap" />
                </td></tr>
            @endforelse
        </tbody>
    </x-ui.table>
</div>
