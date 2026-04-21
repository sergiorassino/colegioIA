# Skill: Matricular un alumno en un curso del año

## Contexto

La matriculación crea:
1. Un registro en `matricula` (el alumno en el curso del año actual)
2. Un registro en `calificaciones` por cada materia del curso (con todos los IDs cargados)

Todo en una única transacción.

## Flujo MatricularAlumnoService

```php
<?php

namespace App\Services;

use App\Models\Core\Matricula;
use App\Models\Core\Calificacion;
use App\Models\Core\Materia;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class MatricularAlumnoService
{
    public function matricular(array $datos): Matricula
    {
        // $datos contiene: idLegajos, idCursos, idTerlec, idNivel, idCondiciones,
        //                   nroMatricula, fechaMatricula, obsMatr

        return DB::transaction(function () use ($datos) {
            // 1. Validar que no esté ya matriculado en el mismo terlec + nivel
            $yaExiste = Matricula::where('idLegajos', $datos['idLegajos'])
                ->where('idTerlec', $datos['idTerlec'])
                ->where('idNivel', $datos['idNivel'])
                ->exists();

            if ($yaExiste) {
                throw new RuntimeException(
                    'El alumno ya está matriculado en este ciclo lectivo y nivel.'
                );
            }

            // 2. Crear registro en matricula
            $matricula = Matricula::create([
                'idLegajos'      => $datos['idLegajos'],
                'idCursos'       => $datos['idCursos'],
                'idTerlec'       => $datos['idTerlec'],
                'idNivel'        => $datos['idNivel'],
                'idCondiciones'  => $datos['idCondiciones'],
                'nroMatricula'   => $datos['nroMatricula'] ?? null,
                'fechaMatricula' => $datos['fechaMatricula'] ?? now()->toDateString(),
                'obsMatr'        => $datos['obsMatr'] ?? null,
            ]);

            // 3. Obtener todas las materias del curso
            $materias = Materia::where('idCursos', $datos['idCursos'])
                ->orderBy('ord')
                ->get();

            // 4. Crear un registro de calificaciones por cada materia
            foreach ($materias as $materia) {
                Calificacion::create([
                    'idLegajos'   => $datos['idLegajos'],
                    'idMatricula' => $matricula->id,
                    'idTerlec'    => $datos['idTerlec'],
                    'idCursos'    => $datos['idCursos'],
                    'idMaterias'  => $materia->id,
                    'idMatPlan'   => $materia->idMatPlan,
                    'ord'         => $materia->ord,
                    // ic01..ic40, tm1..tm6, dic, feb → default '' (vacíos)
                ]);
            }

            return $matricula;
        });
    }
}
```

## Componente Livewire

```php
<?php

namespace App\Livewire\Core;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\MatricularAlumnoService;
use App\Models\Core\Legajo;
use App\Models\Core\Curso;
use App\Auth\ContextoStaff;

class MatricularAlumno extends Component
{
    use WithPagination;

    // Búsqueda de alumno
    public string $buscarAlumno = '';
    public ?int $legajoSeleccionado = null;
    public ?Legajo $legajo = null;

    // Selección de curso
    public ?int $cursoSeleccionado = null;

    // Datos de la matrícula
    public int $idCondiciones = 1; // REGULAR por defecto
    public string $nroMatricula = '';
    public string $fechaMatricula = '';
    public string $obsMatr = '';

    // Estado UI
    public string $paso = 'buscar'; // buscar → alumno → curso → confirmar
    public ?string $error = null;

    public function render()
    {
        $alumnos = $this->buscarAlumno
            ? Legajo::where(function($q) {
                    $q->where('apellido', 'like', "%{$this->buscarAlumno}%")
                      ->orWhere('nombre', 'like', "%{$this->buscarAlumno}%")
                      ->orWhere('dni', 'like', "%{$this->buscarAlumno}%");
                })
                ->orderBy('apellido')
                ->limit(20)
                ->get()
            : collect();

        $cursos = Curso::with('curPlan')
            ->where('idTerlec', session('terlec_id'))
            ->where('idNivel', session('nivel_id'))
            ->orderBy('orden')
            ->get();

        return view('livewire.core.matricular-alumno', compact('alumnos', 'cursos'))
            ->layout('layouts.staff');
    }

    public function seleccionarAlumno(int $id): void
    {
        $this->legajoSeleccionado = $id;
        $this->legajo = Legajo::findOrFail($id);
        $this->paso = 'curso';
    }

    public function matricular(): void
    {
        try {
            app(MatricularAlumnoService::class)->matricular([
                'idLegajos'      => $this->legajoSeleccionado,
                'idCursos'       => $this->cursoSeleccionado,
                'idTerlec'       => session('terlec_id'),
                'idNivel'        => session('nivel_id'),
                'idCondiciones'  => $this->idCondiciones,
                'nroMatricula'   => $this->nroMatricula,
                'fechaMatricula' => $this->fechaMatricula ?: now()->toDateString(),
                'obsMatr'        => $this->obsMatr,
            ]);
            $this->dispatch('toast', type: 'success', message: 'Alumno matriculado correctamente.');
            $this->reset();
            $this->paso = 'buscar';
        } catch (\RuntimeException $e) {
            $this->error = $e->getMessage();
        }
    }
}
```

## Tests requeridos

```php
// tests/Feature/Nucleo/MatricularAlumnoTest.php
it('crea matricula y N calificaciones en una transacción', function () {
    $curso = Curso::factory()
        ->has(Materia::factory()->count(8), 'materias')
        ->create();
    $legajo = Legajo::factory()->create();

    $matricula = app(MatricularAlumnoService::class)->matricular([
        'idLegajos'     => $legajo->id,
        'idCursos'      => $curso->Id,
        'idTerlec'      => $curso->idTerlec,
        'idNivel'       => $curso->idNivel,
        'idCondiciones' => 1,
    ]);

    expect(Matricula::where('idLegajos', $legajo->id)->count())->toBe(1);
    expect(Calificacion::where('idMatricula', $matricula->id)->count())->toBe(8);
    expect(Calificacion::where('idMatricula', $matricula->id)->where('idMatPlan', 0)->count())->toBe(0);
});

it('rechaza matricula duplicada en mismo terlec+nivel', function () {
    $curso = Curso::factory()->has(Materia::factory()->count(2), 'materias')->create();
    $legajo = Legajo::factory()->create();
    $datos = ['idLegajos' => $legajo->id, 'idCursos' => $curso->Id,
              'idTerlec' => $curso->idTerlec, 'idNivel' => $curso->idNivel, 'idCondiciones' => 1];

    app(MatricularAlumnoService::class)->matricular($datos);

    expect(fn() => app(MatricularAlumnoService::class)->matricular($datos))
        ->toThrow(RuntimeException::class);
});
```
