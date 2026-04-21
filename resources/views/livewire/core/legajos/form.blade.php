<div x-data>
    {{-- Header --}}
    <x-ui.page-header
        :title="$id ? 'Legajo: ' . $apellido . ', ' . $nombre : 'Nuevo legajo'"
        :breadcrumbs="[
            ['label' => 'Inicio',   'href' => route('staff.dashboard')],
            ['label' => 'Legajos', 'href' => route('staff.legajos.index')],
            ['label' => $id ? $apellido . ', ' . $nombre : 'Nuevo'],
        ]"
    >
        <x-slot:actions>
            <a href="{{ route('staff.legajos.index') }}" wire:navigate>
                <x-ui.button variant="ghost">← Volver</x-ui.button>
            </a>
            <x-ui.button variant="primary" icon="check" wire:click="guardar">Guardar</x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    @if(session('success'))
        <x-ui.alert variant="success" class="mb-5">{{ session('success') }}</x-ui.alert>
    @endif

    {{-- Tabs --}}
    <div class="border-b border-neutral-200 mb-6 overflow-x-auto">
        <nav class="-mb-px flex gap-1 min-w-max" aria-label="Secciones del legajo">
            @foreach([
                ['key' => 'personal',   'label' => 'Personal',     'num' => '①'],
                ['key' => 'madre',      'label' => 'Madre',        'num' => '②'],
                ['key' => 'padre',      'label' => 'Padre',        'num' => '③'],
                ['key' => 'tutor',      'label' => 'Tutor',        'num' => '④'],
                ['key' => 'respAdmi',   'label' => 'Resp. Admi.', 'num' => '⑤'],
                ['key' => 'reglamento', 'label' => 'Reglamento',   'num' => '⑥'],
            ] as $tab)
                <button wire:click="setTab('{{ $tab['key'] }}')"
                        @class([
                            'inline-flex items-center gap-1.5 py-3 px-4 border-b-2 text-sm font-medium whitespace-nowrap transition-colors',
                            'border-primary-500 text-primary-600' => $activeTab === $tab['key'],
                            'border-transparent text-neutral-500 hover:border-neutral-300 hover:text-neutral-700' => $activeTab !== $tab['key'],
                        ])
                        aria-selected="{{ $activeTab === $tab['key'] ? 'true' : 'false' }}"
                        role="tab">
                    <span class="text-xs opacity-60">{{ $tab['num'] }}</span>
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </nav>
    </div>

    {{-- ① Personal --}}
    @if($activeTab === 'personal')
        <x-ui.card>
            <x-slot:title>Datos personales del alumno/a</x-slot:title>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="sm:col-span-2 lg:col-span-1">
                    <label class="label label-required">Apellido</label>
                    <input type="text" wire:model.blur="apellido" maxlength="50"
                           class="input @error('apellido') input-error @enderror"
                           @error('apellido') aria-invalid="true" @enderror />
                    @error('apellido') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2 lg:col-span-1">
                    <label class="label label-required">Nombre</label>
                    <input type="text" wire:model.blur="nombre" maxlength="50"
                           class="input @error('nombre') input-error @enderror"
                           @error('nombre') aria-invalid="true" @enderror />
                    @error('nombre') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">DNI</label>
                    <input type="number" wire:model.blur="dni"
                           class="input @error('dni') input-error @enderror"
                           @error('dni') aria-invalid="true" @enderror />
                    @error('dni') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">CUIL</label>
                    <input type="text" wire:model.blur="cuil" maxlength="13" class="input" placeholder="20-12345678-9" />
                </div>
                <div>
                    <label class="label">Fecha de nacimiento</label>
                    <input type="date" wire:model.blur="fechnaci" class="input" />
                </div>
                <div>
                    <label class="label">Sexo</label>
                    <select wire:model.blur="sexo" class="input">
                        <option value="">Sin especificar</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="X">No binario</option>
                    </select>
                </div>
                <div>
                    <label class="label label-required">Nivel</label>
                    <select wire:model.blur="idnivel" class="input @error('idnivel') input-error @enderror"
                            @error('idnivel') aria-invalid="true" @enderror>
                        <option value="0">Seleccione…</option>
                        @foreach($this->niveles as $n)
                            <option value="{{ $n->id }}">{{ $n->nivel }}</option>
                        @endforeach
                    </select>
                    @error('idnivel') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Nacionalidad</label>
                    <input type="text" wire:model.blur="nacion" maxlength="20" class="input" />
                </div>
                <div class="sm:col-span-2">
                    <label class="label">Calle y número</label>
                    <input type="text" wire:model.blur="callenum" maxlength="50" class="input" />
                </div>
                <div>
                    <label class="label">Barrio</label>
                    <input type="text" wire:model.blur="barrio" maxlength="50" class="input" />
                </div>
                <div>
                    <label class="label">Localidad</label>
                    <input type="text" wire:model.blur="localidad" maxlength="50" class="input" />
                </div>
                <div>
                    <label class="label">Teléfono</label>
                    <input type="text" wire:model.blur="telefono" maxlength="60" class="input" />
                </div>
                <div class="sm:col-span-2">
                    <label class="label">Email del alumno/a</label>
                    <input type="email" wire:model.blur="email" maxlength="100"
                           class="input @error('email') input-error @enderror"
                           @error('email') aria-invalid="true" @enderror />
                    @error('email') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="label">Vive con</label>
                    <input type="text" wire:model.blur="vivecon" maxlength="200" class="input" />
                </div>
                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="label">Quiénes retiran al alumno/a</label>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
                        <input type="text" wire:model.blur="retira"  placeholder="Persona 1" class="input text-sm" />
                        <input type="text" wire:model.blur="retira1" placeholder="Persona 2" class="input text-sm" />
                        <input type="text" wire:model.blur="retira2" placeholder="Persona 3" class="input text-sm" />
                    </div>
                </div>
                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="label">Observaciones</label>
                    <textarea wire:model.blur="obs" rows="3" class="input resize-none"></textarea>
                </div>
            </div>
        </x-ui.card>
    @endif

    {{-- ② Madre --}}
    @if($activeTab === 'madre')
        <x-ui.card>
            <x-slot:title>Datos de la madre</x-slot:title>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="sm:col-span-2">
                    <label class="label">Nombre completo</label>
                    <input type="text" wire:model.blur="nombremad" maxlength="50" class="input" />
                </div>
                <div>
                    <label class="label">DNI</label>
                    <input type="text" wire:model.blur="dnimad" maxlength="10" class="input" />
                </div>
                <div>
                    <label class="label">¿Vive?</label>
                    <select wire:model.blur="vivemad" class="input">
                        <option value="">-</option>
                        <option value="Si">Sí</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div>
                    <label class="label">Fecha de nacimiento</label>
                    <input type="date" wire:model.blur="fechnacmad" class="input" />
                </div>
                <div>
                    <label class="label">Teléfono</label>
                    <input type="text" wire:model.blur="telemad" maxlength="50" class="input" />
                </div>
                <div>
                    <label class="label">Celular</label>
                    <input type="text" wire:model.blur="telecelmad" maxlength="50" class="input" />
                </div>
                <div>
                    <label class="label">Email</label>
                    <input type="email" wire:model.blur="emailmad" maxlength="50"
                           class="input @error('emailmad') input-error @enderror" />
                    @error('emailmad') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Ocupación</label>
                    <input type="text" wire:model.blur="ocupacmad" maxlength="30" class="input" />
                </div>
                <div>
                    <label class="label">Lugar de trabajo</label>
                    <input type="text" wire:model.blur="lugtramad" maxlength="30" class="input" />
                </div>
            </div>
        </x-ui.card>
    @endif

    {{-- ③ Padre --}}
    @if($activeTab === 'padre')
        <x-ui.card>
            <x-slot:title>Datos del padre</x-slot:title>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="sm:col-span-2">
                    <label class="label">Nombre completo</label>
                    <input type="text" wire:model.blur="nombrepad" maxlength="50" class="input" />
                </div>
                <div>
                    <label class="label">DNI</label>
                    <input type="text" wire:model.blur="dnipad" maxlength="10" class="input" />
                </div>
                <div>
                    <label class="label">¿Vive?</label>
                    <select wire:model.blur="vivepad" class="input">
                        <option value="">-</option>
                        <option value="Si">Sí</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div>
                    <label class="label">Fecha de nacimiento</label>
                    <input type="date" wire:model.blur="fechnacpad" class="input" />
                </div>
                <div>
                    <label class="label">Teléfono</label>
                    <input type="text" wire:model.blur="telepad" maxlength="50" class="input" />
                </div>
                <div>
                    <label class="label">Celular</label>
                    <input type="text" wire:model.blur="telecelpad" maxlength="50" class="input" />
                </div>
                <div>
                    <label class="label">Email</label>
                    <input type="email" wire:model.blur="emailpad" maxlength="50"
                           class="input @error('emailpad') input-error @enderror" />
                    @error('emailpad') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Ocupación</label>
                    <input type="text" wire:model.blur="ocupacpad" maxlength="30" class="input" />
                </div>
                <div>
                    <label class="label">Lugar de trabajo</label>
                    <input type="text" wire:model.blur="lugtrapad" maxlength="30" class="input" />
                </div>
            </div>
        </x-ui.card>
    @endif

    {{-- ④ Tutor --}}
    @if($activeTab === 'tutor')
        <x-ui.card>
            <x-slot:title>Datos del tutor/a</x-slot:title>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="sm:col-span-2">
                    <label class="label">Nombre completo</label>
                    <input type="text" wire:model.blur="nombretut" maxlength="50" class="input" />
                </div>
                <div>
                    <label class="label">DNI</label>
                    <input type="number" wire:model.blur="dnitut" class="input" />
                </div>
                <div>
                    <label class="label">Teléfono</label>
                    <input type="text" wire:model.blur="teletut" maxlength="20" class="input" />
                </div>
                <div>
                    <label class="label">Email</label>
                    <input type="email" wire:model.blur="emailtut" maxlength="50"
                           class="input @error('emailtut') input-error @enderror" />
                    @error('emailtut') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Ocupación</label>
                    <input type="text" wire:model.blur="ocupactut" maxlength="50" class="input" />
                </div>
            </div>
        </x-ui.card>
    @endif

    {{-- ⑤ Resp. Admi. --}}
    @if($activeTab === 'respAdmi')
        <x-ui.card>
            <x-slot:title>Responsable administrativo</x-slot:title>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="label">Apellido y nombre</label>
                    <input type="text" wire:model.blur="respAdmiNom" maxlength="100" class="input" />
                </div>
                <div>
                    <label class="label">DNI</label>
                    <input type="number" wire:model.blur="respAdmiDni" class="input" />
                </div>
            </div>
        </x-ui.card>
    @endif

    {{-- ⑥ Reglamento --}}
    @if($activeTab === 'reglamento')
        <x-ui.card>
            <x-slot:title>Aceptación del reglamento institucional</x-slot:title>
            <p class="text-sm text-neutral-500 mb-4">Datos de quien aceptó el reglamento institucional.</p>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="label">Apellido y nombre</label>
                    <input type="text" wire:model.blur="reglamApenom" maxlength="100" class="input" />
                </div>
                <div>
                    <label class="label">DNI</label>
                    <input type="number" wire:model.blur="reglamDni" class="input" />
                </div>
                <div>
                    <label class="label">Email</label>
                    <input type="email" wire:model.blur="reglamEmail" maxlength="70"
                           class="input @error('reglamEmail') input-error @enderror" />
                    @error('reglamEmail') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
            </div>
        </x-ui.card>
    @endif

    {{-- Botón guardar inferior fijo --}}
    <div class="mt-6 flex items-center justify-between gap-4 pt-4 border-t border-neutral-100 sticky bottom-0 bg-neutral-50 py-4 px-0 -mx-0">
        <a href="{{ route('staff.legajos.index') }}" wire:navigate>
            <x-ui.button variant="ghost">← Volver al listado</x-ui.button>
        </a>
        <x-ui.button variant="primary" icon="check" wire:click="guardar">Guardar cambios</x-ui.button>
    </div>
</div>
