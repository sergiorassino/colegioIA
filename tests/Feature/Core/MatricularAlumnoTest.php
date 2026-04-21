<?php

use App\Models\Core\Calificacion;
use App\Models\Core\Condicion;
use App\Models\Core\CurPlan;
use App\Models\Core\Curso;
use App\Models\Core\Legajo;
use App\Models\Core\Materia;
use App\Models\Core\MatPlan;
use App\Models\Core\Matricula;
use App\Models\Core\Nivel;
use App\Models\Core\Plan;
use App\Models\Core\Terlec;
use App\Services\MatricularAlumnoService;

beforeEach(function () {
    $this->nivel      = Nivel::factory()->create();
    $this->terlec     = Terlec::factory()->create();
    $plan             = Plan::factory()->create(['idNivel' => $this->nivel->id]);
    $curplan          = CurPlan::factory()->create(['idPlan' => $plan->id]);
    $this->curso      = Curso::factory()->create([
        'idTerlec'  => $this->terlec->id,
        'idNivel'   => $this->nivel->id,
        'idCurPlan' => $curplan->id,
    ]);

    // Crear 3 materias en el curso
    MatPlan::factory()->count(3)->create(['idCurPlan' => $curplan->id])
        ->each(fn ($mp) => Materia::factory()->create([
            'idCursos'  => $this->curso->Id,
            'idCurPlan' => $curplan->id,
            'idMatPlan' => $mp->id,
            'idTerlec'  => $this->terlec->id,
            'idNivel'   => $this->nivel->id,
        ]));

    $this->condicion = Condicion::factory()->create(['condicion' => 'REGULAR', 'proteg' => 0]);
    $this->legajo    = Legajo::factory()->create(['idnivel' => $this->nivel->id]);
    $this->servicio  = app(MatricularAlumnoService::class);
});

it('matricula un alumno y crea calificaciones por cada materia', function () {
    $matricula = $this->servicio->matricular([
        'idLegajos'     => $this->legajo->id,
        'idTerlec'      => $this->terlec->id,
        'idNivel'       => $this->nivel->id,
        'idCursos'      => $this->curso->Id,
        'idCondiciones' => $this->condicion->id,
        'fechaMatricula'=> now()->toDateString(),
    ]);

    expect($matricula)->toBeInstanceOf(Matricula::class);

    $califs = Calificacion::where('idMatricula', $matricula->id)->get();
    expect($califs)->toHaveCount(3);
});

it('impide matricular el mismo alumno dos veces en el mismo terlec', function () {
    $this->servicio->matricular([
        'idLegajos'     => $this->legajo->id,
        'idTerlec'      => $this->terlec->id,
        'idNivel'       => $this->nivel->id,
        'idCursos'      => $this->curso->Id,
        'idCondiciones' => $this->condicion->id,
    ]);

    expect(fn () => $this->servicio->matricular([
        'idLegajos'     => $this->legajo->id,
        'idTerlec'      => $this->terlec->id,
        'idNivel'       => $this->nivel->id,
        'idCursos'      => $this->curso->Id,
        'idCondiciones' => $this->condicion->id,
    ]))->toThrow(\RuntimeException::class);
});

it('cancelar elimina la matrícula y las calificaciones', function () {
    $matricula = $this->servicio->matricular([
        'idLegajos'     => $this->legajo->id,
        'idTerlec'      => $this->terlec->id,
        'idNivel'       => $this->nivel->id,
        'idCursos'      => $this->curso->Id,
        'idCondiciones' => $this->condicion->id,
    ]);

    $this->servicio->cancelar($matricula->id);

    expect(Matricula::find($matricula->id))->toBeNull();
    expect(Calificacion::where('idMatricula', $matricula->id)->count())->toBe(0);
});
