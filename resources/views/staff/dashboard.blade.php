<x-slot:title>Dashboard — {{ config('app.name') }}</x-slot:title>

<div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Panel de gestión</h1>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @auth('staff')
            @php
                $profesor = auth('staff')->user();
                $nivel    = session('nivel_id')  ? \App\Models\Core\Nivel::find(session('nivel_id'))  : null;
                $terlec   = session('terlec_id') ? \App\Models\Core\Terlec::find(session('terlec_id')) : null;
            @endphp

            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Usuario</dt>
                <dd class="mt-1 text-xl font-semibold text-gray-900">{{ $profesor->nombre ?? $profesor->dni }}</dd>
            </div>

            @if($nivel)
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Nivel activo</dt>
                <dd class="mt-1 text-xl font-semibold text-gray-900">{{ $nivel->nombre }}</dd>
            </div>
            @endif

            @if($terlec)
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Ciclo lectivo</dt>
                <dd class="mt-1 text-xl font-semibold text-gray-900">{{ $terlec->ano }}</dd>
            </div>
            @endif
        @endauth
    </div>
</div>
