@auth('alumno')
    @php
        $alumno = auth('alumno')->user();
    @endphp
    <div>
        <h1 class="text-xl font-bold text-gray-900 mb-4">
            Bienvenido/a, {{ $alumno->nombre ?? $alumno->apellido ?? $alumno->dni }}
        </h1>

        <div class="rounded-xl bg-white shadow p-5">
            <p class="text-sm text-gray-500">Aquí verás tu información de matrícula, notas y más.</p>
        </div>
    </div>
@endauth
