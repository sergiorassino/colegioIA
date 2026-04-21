# Skill: Generar curso del año + edición de materias

## Contexto

Al crear un curso del año en `cursos`, se replican las materias modelo (`matplan`) en la tabla `materias`.
La relación `materias.idMatPlan → matplan.id` es CRÍTICA para el sistema de previas.

## Flujo GenerarCursoService

```php
<?php

namespace App\Services;

use App\Models\Core\Curso;
use App\Models\Core\CurPlan;
use App\Models\Core\Materia;
use Illuminate\Support\Facades\DB;

class GenerarCursoService
{
    public function crear(
        int $curPlanId,
        int $terlecId,
        int $nivelId,
        array $datos // cursec, c, s, turno, orden
    ): Curso {
        return DB::transaction(function () use ($curPlanId, $terlecId, $nivelId, $datos) {
            // 1. Crear el curso del año
            $curso = Curso::create([
                'idCurPlan' => $curPlanId,
                'idTerlec'  => $terlecId,
                'idNivel'   => $nivelId,
                'cursec'    => $datos['cursec'],
                'c'         => $datos['c'] ?? null,
                's'         => $datos['s'] ?? null,
                'turno'     => $datos['turno'] ?? null,
                'orden'     => $datos['orden'] ?? 0,
            ]);

            // 2. Obtener materias modelo del curplan
            $matplans = CurPlan::findOrFail($curPlanId)
                ->matplans()
                ->orderBy('ord')
                ->get();

            // 3. Replicar cada matplan como materia del año
            foreach ($matplans as $matplan) {
                Materia::create([
                    'idCurPlan' => $curPlanId,
                    'idMatPlan' => $matplan->id,   // ← VÍNCULO CRÍTICO
                    'idNivel'   => $nivelId,
                    'idCursos'  => $curso->Id,     // ← PK mayúscula de cursos
                    'idTerlec'  => $terlecId,
                    'materia'   => $matplan->matPlanMateria,
                    'abrev'     => $matplan->abrev,
                    'ord'       => $matplan->ord,
                ]);
            }

            return $curso;
        });
    }
}
```

## Flujo MateriaIntegridadService

```php
<?php

namespace App\Services;

use App\Models\Core\Materia;
use App\Models\Core\MatPlan;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class MateriaIntegridadService
{
    /**
     * Renombrar una materia del año (y opcionalmente su matplan).
     */
    public function renombrar(Materia $materia, string $nuevoNombre, bool $propagarAMatplan = false): void
    {
        DB::transaction(function () use ($materia, $nuevoNombre, $propagarAMatplan) {
            $materia->update(['materia' => $nuevoNombre]);

            if ($propagarAMatplan && $materia->idMatPlan > 0) {
                MatPlan::findOrFail($materia->idMatPlan)->update(['matPlanMateria' => $nuevoNombre]);
            }
        });
    }

    /**
     * Agregar una materia al año con su matplan correspondiente.
     */
    public function agregarConModelo(Curso $curso, string $nombre, array $datos = []): Materia
    {
        return DB::transaction(function () use ($curso, $nombre, $datos) {
            // Primero crear el matplan
            $matplan = MatPlan::create([
                'idCurPlan'      => $curso->idCurPlan,
                'matPlanMateria' => $nombre,
                'ord'            => $datos['ord'] ?? 99,
                'abrev'          => $datos['abrev'] ?? null,
                'codGE'          => $datos['codGE'] ?? null,
            ]);

            // Luego la materia vinculada
            return Materia::create([
                'idCurPlan' => $curso->idCurPlan,
                'idMatPlan' => $matplan->id,   // ← vínculo correcto
                'idNivel'   => $curso->idNivel,
                'idCursos'  => $curso->Id,
                'idTerlec'  => $curso->idTerlec,
                'materia'   => $nombre,
                'abrev'     => $datos['abrev'] ?? null,
                'ord'       => $datos['ord'] ?? 99,
            ]);
        });
    }

    /**
     * Agregar una materia extraordinaria del año (sin modelo).
     */
    public function agregarExtraordinaria(Curso $curso, string $nombre, array $datos = []): Materia
    {
        // idMatPlan = 0 → materia extraordinaria (no vinculada a matplan)
        return Materia::create([
            'idCurPlan' => $curso->idCurPlan,
            'idMatPlan' => 0,  // ← extraordinaria
            'idNivel'   => $curso->idNivel,
            'idCursos'  => $curso->Id,
            'idTerlec'  => $curso->idTerlec,
            'materia'   => $nombre,
            'abrev'     => $datos['abrev'] ?? null,
            'ord'       => $datos['ord'] ?? 99,
        ]);
        // TODO: registrar en tabla de auditoría
    }

    /**
     * Quitar una materia del año (y opcionalmente de matplan).
     */
    public function quitar(Materia $materia, bool $quitarDeMatplan = false): void
    {
        DB::transaction(function () use ($materia, $quitarDeMatplan) {
            if ($quitarDeMatplan && $materia->idMatPlan > 0) {
                $tieneOtrasRefs = Materia::where('idMatPlan', $materia->idMatPlan)
                    ->where('id', '!=', $materia->id)
                    ->exists();

                if ($tieneOtrasRefs) {
                    throw new RuntimeException(
                        'No se puede eliminar de matplan: hay otras materias en otros años que la referencian.'
                    );
                }

                MatPlan::find($materia->idMatPlan)?->delete();
            }

            $materia->delete();
        });
    }

    /**
     * Prohibido: no se puede cambiar idMatPlan de una materia con vínculo existente.
     */
    public function cambiarIdMatPlan(Materia $materia, int $nuevoIdMatPlan): never
    {
        throw new RuntimeException(
            'No se puede cambiar idMatPlan de una materia existente. ' .
            'Ver regla 070-integridad-curso-materia.mdc'
        );
    }
}
```

## Tests requeridos

```php
// tests/Feature/Nucleo/GenerarCursoTest.php
it('replica todas las matplan como materias al crear un curso', function () {
    $curplan = CurPlan::factory()->has(MatPlan::factory()->count(5), 'matplans')->create();
    $terlec = Terlec::factory()->activo()->create();
    $nivel = Nivel::factory()->create();

    $curso = app(GenerarCursoService::class)->crear(
        $curplan->id, $terlec->id, $nivel->id,
        ['cursec' => '1ro A', 'c' => '1', 's' => 'A']
    );

    expect(Materia::where('idCursos', $curso->Id)->count())->toBe(5);
    expect(Materia::where('idCursos', $curso->Id)->where('idMatPlan', 0)->count())->toBe(0);
});

it('hace rollback si falla la inserción de una materia', function () {
    // Mock que lanza excepción al tercer matplan
    // Verificar que cursos tampoco quedó creado
});
```
