<div>
    <x-ui.page-header
        title="Planes de estudio"
        subtitle="Planes de estudio por nivel"
        :breadcrumbs="[['label' => 'Inicio', 'href' => route('staff.dashboard')], ['label' => 'Planes']]"
    >
        <x-slot:actions>
            @if(!$showForm)
                <x-ui.button wire:click="crear" icon="plus">Nuevo plan</x-ui.button>
            @endif
        </x-slot:actions>
    </x-ui.page-header>

    @if(session('success')) <x-ui.alert variant="success" class="mb-4">{{ session('success') }}</x-ui.alert> @endif
    @if(session('error'))   <x-ui.alert variant="danger"  class="mb-4">{{ session('error') }}</x-ui.alert>   @endif

    @if($showForm)
        <x-ui.card class="mb-6">
            <x-slot:title>{{ $editId ? 'Editar plan' : 'Nuevo plan de estudio' }}</x-slot:title>
            <form wire:submit="guardar" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="label label-required">Nivel</label>
                    <select wire:model.blur="idNivel" class="input @error('idNivel') input-error @enderror"
                            @error('idNivel') aria-invalid="true" @enderror>
                        <option value="">Seleccione un nivel…</option>
                        @foreach($this->niveles as $n)
                            <option value="{{ $n->id }}">{{ $n->nivel }}</option>
                        @endforeach
                    </select>
                    @error('idNivel') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Abreviatura <span class="text-neutral-400 font-normal">(hasta 5 car.)</span></label>
                    <input type="text" wire:model.blur="abrev" maxlength="5"
                           class="input @error('abrev') input-error @enderror" placeholder="ES"
                           @error('abrev') aria-invalid="true" @enderror />
                    @error('abrev') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="label label-required">Nombre del plan</label>
                    <input type="text" wire:model.blur="plan" maxlength="70"
                           class="input @error('plan') input-error @enderror" placeholder="Plan de Estudios Orientado"
                           @error('plan') aria-invalid="true" @enderror />
                    @error('plan') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
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
                <x-ui.th>Nivel</x-ui.th>
                <x-ui.th>Plan</x-ui.th>
                <x-ui.th>Abrev.</x-ui.th>
                <x-ui.th right>Acciones</x-ui.th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100">
            @forelse($this->planes as $plan)
                <tr class="tr-hover">
                    <x-ui.td><x-ui.badge variant="primary">{{ $plan->nivel?->nivel }}</x-ui.badge></x-ui.td>
                    <x-ui.td><span class="font-medium text-brand-jet">{{ $plan->plan }}</span></x-ui.td>
                    <x-ui.td>{{ $plan->abrev }}</x-ui.td>
                    <x-ui.td right>
                        @if($confirmDeleteId == $plan->id)
                            <div class="flex items-center justify-end gap-2">
                                <span class="text-xs text-danger-600 font-medium">¿Confirmar borrado?</span>
                                <x-ui.button size="sm" variant="danger" wire:click="borrar">Sí</x-ui.button>
                                <x-ui.button size="sm" variant="ghost" wire:click="cancelar">No</x-ui.button>
                            </div>
                        @else
                            <div class="flex items-center justify-end gap-1">
                                <button class="btn-icon" wire:click="editar({{ $plan->id }})" title="Editar"><x-icons.pencil class="w-4 h-4"/></button>
                                <button class="btn-icon-danger" wire:click="confirmarBorrar({{ $plan->id }})" title="Borrar"><x-icons.trash class="w-4 h-4"/></button>
                            </div>
                        @endif
                    </x-ui.td>
                </tr>
            @empty
                <tr><td colspan="4" class="p-0">
                    <x-ui.empty-state title="Sin planes" message="No hay planes registrados aún." icon="document-text" />
                </td></tr>
            @endforelse
        </tbody>
    </x-ui.table>
</div>
