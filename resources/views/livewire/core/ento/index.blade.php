<div>
    <x-ui.page-header
        title="Entorno institucional"
        subtitle="Configure los datos institucionales y parámetros de autogestión por nivel"
        :breadcrumbs="[['label' => 'Inicio', 'href' => route('staff.dashboard')], ['label' => 'Entorno']]"
    />

    @if(session('success')) <x-ui.alert variant="success" class="mb-4">{{ session('success') }}</x-ui.alert> @endif
    @if(session('error'))   <x-ui.alert variant="danger"  class="mb-4">{{ session('error') }}</x-ui.alert>   @endif

    {{-- Formulario de edición --}}
    @if($showForm)
        <x-ui.card class="mb-6">
            <x-slot:title>Editar entorno</x-slot:title>
            <form wire:submit="guardar" class="space-y-6">

                <x-ui.section title="Datos institucionales">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="label">Nivel</label>
                            <select wire:model="idNivel" class="input" disabled>
                                @foreach($this->niveles as $n)
                                    <option value="{{ $n->id }}">{{ $n->nivel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="label">CUE</label>
                            <input type="text" wire:model.blur="cue" maxlength="20" class="input" placeholder="123456789" />
                        </div>
                        <div class="sm:col-span-2">
                            <label class="label">Nombre de la institución</label>
                            <input type="text" wire:model.blur="insti" maxlength="255" class="input" />
                        </div>
                        <div>
                            <label class="label">CUIT</label>
                            <input type="text" wire:model.blur="cuit" maxlength="14" class="input @error('cuit') input-error @enderror" placeholder="30-12345678-9" />
                            @error('cuit') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label">Teléfono</label>
                            <input type="tel" wire:model.blur="telefono" maxlength="50" class="input" />
                        </div>
                        <div>
                            <label class="label">Dirección</label>
                            <input type="text" wire:model.blur="direccion" maxlength="100" class="input" />
                        </div>
                        <div>
                            <label class="label">Localidad</label>
                            <input type="text" wire:model.blur="localidad" maxlength="100" class="input" />
                        </div>
                        <div class="sm:col-span-2">
                            <label class="label">Email institucional</label>
                            <input type="email" wire:model.blur="mail" maxlength="100" class="input @error('mail') input-error @enderror" />
                            @error('mail') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                        </div>
                    </div>
                </x-ui.section>

                <x-ui.section title="Autogestión" description="Define el ciclo lectivo que verán alumnos y docentes en la plataforma de autogestión">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="label">Ciclo lectivo de autogestión</label>
                            <select wire:model.blur="idTerlecVerNotas" class="input @error('idTerlecVerNotas') input-error @enderror">
                                <option value="">Sin asignar</option>
                                @foreach($this->terlecs as $terlec)
                                    <option value="{{ $terlec->id }}">{{ $terlec->ano }}</option>
                                @endforeach
                            </select>
                            @error('idTerlecVerNotas') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                        </div>
                    </div>
                </x-ui.section>

                <x-ui.section title="Disponibilidad del sistema" description="Controla qué funciones están habilitadas en la plataforma">
                    <div class="space-y-3">
                        @foreach([
                            ['model' => 'platOff',        'label' => 'Plataforma de autogestión deshabilitada'],
                            ['model' => 'verNotasOff',    'label' => 'Visualización de notas deshabilitada'],
                            ['model' => 'cargaNotasOff',  'label' => 'Carga de calificaciones deshabilitada'],
                            ['model' => 'matriculaWebOff','label' => 'Matrícula web deshabilitada'],
                        ] as $flag)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" wire:model="{{ $flag['model'] }}"
                                       class="h-4 w-4 rounded border-neutral-300 text-primary-600 focus:ring-primary-500" />
                                <span class="text-sm text-neutral-700 group-hover:text-brand-jet transition-colors">
                                    {{ $flag['label'] }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <label class="label">Mensaje cuando la plataforma está deshabilitada</label>
                        <textarea wire:model.blur="offMensaje" rows="2" maxlength="500"
                                  class="input resize-none" placeholder="El sistema no está disponible en este momento…"></textarea>
                    </div>
                </x-ui.section>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-100">
                    <x-ui.button type="button" variant="ghost" wire:click="cancelar">Cancelar</x-ui.button>
                    <x-ui.button type="submit" variant="primary" icon="check">Guardar cambios</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    @endif

    {{-- Grid de entornos --}}
    @if(!$showForm)
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($this->entos as $ento)
                <div class="card hover:shadow-md transition-shadow">
                    <div class="card-body">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <x-ui.badge variant="primary">{{ $ento->nivel?->abrev }}</x-ui.badge>
                                <h3 class="mt-2 text-sm font-semibold text-brand-jet">{{ $ento->nivel?->nivel }}</h3>
                                <p class="text-xs text-neutral-500 mt-0.5">{{ $ento->insti }}</p>
                            </div>
                            <button class="btn-icon" wire:click="editar({{ $ento->Id }})" title="Editar">
                                <x-icons.pencil class="w-4 h-4" />
                            </button>
                        </div>

                        <dl class="space-y-1.5 text-xs">
                            @if($ento->terlecAutogesion)
                                <div class="flex items-center gap-1.5">
                                    <x-icons.calendar class="w-3.5 h-3.5 text-primary-400 shrink-0" />
                                    <dd class="text-neutral-600">Autogestión: <strong>{{ $ento->terlecAutogesion->ano }}</strong></dd>
                                </div>
                            @else
                                <div class="flex items-center gap-1.5">
                                    <x-icons.exclamation-triangle class="w-3.5 h-3.5 text-warning-500 shrink-0" />
                                    <dd class="text-warning-600 font-medium">Sin ciclo de autogestión</dd>
                                </div>
                            @endif

                            @if($ento->platOff)
                                <div class="flex items-center gap-1.5">
                                    <x-icons.exclamation-triangle class="w-3.5 h-3.5 text-danger-500 shrink-0" />
                                    <dd class="text-danger-600 font-semibold">Plataforma deshabilitada</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
