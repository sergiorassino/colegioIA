<x-layouts.staff>
    <x-slot:title>Dashboard — {{ config('app.name') }}</x-slot:title>

    @auth('staff')
        @php
            $profesor = auth('staff')->user();
            $nivel    = session('nivel_id')  ? \App\Models\Core\Nivel::find(session('nivel_id'))  : null;
            $terlec   = session('terlec_id') ? \App\Models\Core\Terlec::find(session('terlec_id')) : null;
        @endphp

        <x-ui.page-header
            title="Panel de gestión"
            subtitle="Bienvenido/a al sistema de gestión pedagógica"
        />

        {{-- Tarjetas de contexto --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-8">
            {{-- Usuario --}}
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-primary-500 flex items-center justify-center shrink-0">
                            <span class="text-sm font-bold text-white">
                                {{ strtoupper(substr($profesor->nombre ?? $profesor->dni, 0, 2)) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-2xs text-neutral-500 font-medium uppercase tracking-wide">Usuario</p>
                            <p class="text-sm font-semibold text-brand-jet">{{ $profesor->nombre ?? $profesor->dni }}</p>
                            <p class="text-2xs text-neutral-400">DNI {{ $profesor->dni }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nivel --}}
            @if($nivel)
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-brand-surface flex items-center justify-center shrink-0">
                                <x-icons.academic-cap class="w-5 h-5 text-primary-600" />
                            </div>
                            <div>
                                <p class="text-2xs text-neutral-500 font-medium uppercase tracking-wide">Nivel activo</p>
                                <p class="text-sm font-semibold text-brand-jet">{{ $nivel->nivel }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Ciclo lectivo --}}
            @if($terlec)
                <div class="card">
                    <div class="card-body">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-brand-surface flex items-center justify-center shrink-0">
                                <x-icons.calendar class="w-5 h-5 text-primary-600" />
                            </div>
                            <div>
                                <p class="text-2xs text-neutral-500 font-medium uppercase tracking-wide">Ciclo lectivo</p>
                                <p class="text-sm font-semibold text-brand-jet">{{ $terlec->ano }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Accesos rápidos --}}
        <x-ui.card title="Accesos rápidos">
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                @php
                    $accesos = [
                        ['label' => 'Ciclos lectivos',  'route' => 'staff.terlec.index',   'icon' => 'calendar'],
                        ['label' => 'Niveles',          'route' => 'staff.nivel.index',    'icon' => 'academic-cap'],
                        ['label' => 'Planes',           'route' => 'staff.plan.index',     'icon' => 'document-text'],
                        ['label' => 'Cursos modelo',    'route' => 'staff.curplan.index',  'icon' => 'users'],
                        ['label' => 'Entorno',          'route' => 'staff.ento.index',     'icon' => 'cog-6-tooth'],
                        ['label' => 'Cursos del año',   'route' => 'staff.cursos.index',   'icon' => 'academic-cap'],
                        ['label' => 'Legajos',          'route' => 'staff.legajos.index',  'icon' => 'users'],
                        ['label' => 'Matrículas',       'route' => 'staff.matriculas.index', 'icon' => 'document-text'],
                    ];
                @endphp

                @foreach($accesos as $a)
                    <a href="{{ route($a['route']) }}"
                       wire:navigate
                       class="flex flex-col items-center gap-2 rounded-xl border border-neutral-100 bg-neutral-50 p-4
                              hover:bg-brand-surface/40 hover:border-primary-200 transition-colors group">
                        <div class="h-9 w-9 rounded-full bg-primary-100 flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                            <x-dynamic-component :component="'icons.' . $a['icon']" class="w-4 h-4 text-primary-600" />
                        </div>
                        <span class="text-xs font-medium text-neutral-600 text-center leading-tight">{{ $a['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </x-ui.card>

    @endauth
</x-layouts.staff>
