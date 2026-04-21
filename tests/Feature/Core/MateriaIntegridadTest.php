<?php

use App\Models\Core\CurPlan;
use App\Models\Core\Curso;
use App\Models\Core\Materia;
use App\Models\Core\MatPlan;
use App\Models\Core\Nivel;
use App\Models\Core\Plan;
use App\Models\Core\Terlec;
use App\Services\MateriaIntegridadService;

beforeEach(function () {
    $nivel    = Nivel::factory()->create();
    $terlec   = Terlec::factory()->create();
    $plan     = Plan::factory()->create(['idNivel' => $nivel->id]);
    $curplan  = CurPlan::factory()->create(['idPlan' => $plan->id]);
    $this->curso = Curso::factory()->create([
        'idTerlec' => $terlec->id,
        'idNivel'  => $nivel->id,
        'idCurPlan'=> $curplan->id,
    ]);

    $this->matplan = MatPlan::factory()->create([
        'idCurPlan'      => $curplan->id,
        'matPlanMateria' => 'Matemática',
    ]);

    $this->materia = Materia::factory()->create([
        'idCursos'  => $this->curso->Id,
        'idCurPlan' => $curplan->id,
        'idMatPlan' => $this->matplan->id,
        'idNivel'   => $nivel->id,
        'idTerlec'  => $terlec->id,
        'materia'   => 'Matemática',
    ]);

    $this->servicio = app(MateriaIntegridadService::class);
});

it('renombrar actualiza tanto materia como matplan', function () {
    $this->servicio->renombrar($this->materia->id, 'Matemática Avanzada');

    expect($this->materia->fresh()->materia)->toBe('Matemática Avanzada');
    expect($this->matplan->fresh()->matPlanMateria)->toBe('Matemática Avanzada');
});

it('agregar crea materia y su matplan correspondiente', function () {
    $materia = $this->servicio->agregar(
        idCursos : $this->curso->Id,
        idCurPlan: $this->curso->idCurPlan,
        idNivel  : $this->curso->idNivel,
        idTerlec : $this->curso->idTerlec,
        nombre   : 'Física',
        ord      : 5,
        abrev    : 'Fis',
    );

    expect($materia->materia)->toBe('Física');
    expect(MatPlan::find($materia->idMatPlan)?->matPlanMateria)->toBe('Física');
});

it('eliminar borra materia y su matplan si no hay otras referencias', function () {
    $this->servicio->eliminar($this->materia->id);

    expect(Materia::find($this->materia->id))->toBeNull();
    expect(MatPlan::find($this->matplan->id))->toBeNull();
});

it('eliminar lanza excepción si tiene calificaciones', function () {
    \App\Models\Core\Calificacion::factory()->create([
        'idMaterias' => $this->materia->id,
        'idLegajos'  => \App\Models\Core\Legajo::factory()->create(['idnivel' => $this->curso->idNivel])->id,
    ]);

    expect(fn () => $this->servicio->eliminar($this->materia->id))
        ->toThrow(\RuntimeException::class);
});
