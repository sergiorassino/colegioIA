<?php

use App\Models\Core\CurPlan;
use App\Models\Core\Curso;
use App\Models\Core\Materia;
use App\Models\Core\MatPlan;
use App\Models\Core\Nivel;
use App\Models\Core\Plan;
use App\Models\Core\Terlec;
use App\Services\GenerarCursoService;

beforeEach(function () {
    $this->nivel  = Nivel::factory()->create();
    $this->terlec = Terlec::factory()->create();
    $plan         = Plan::factory()->create(['idNivel' => $this->nivel->id]);
    $this->curplan= CurPlan::factory()->create(['idPlan' => $plan->id]);

    // Crear 3 materias modelo
    $this->matplanes = MatPlan::factory()->count(3)->create([
        'idCurPlan' => $this->curplan->id,
    ]);

    $this->servicio = app(GenerarCursoService::class);
});

it('genera un curso con materias replicadas', function () {
    $curso = $this->servicio->generar([
        'idTerlec' => $this->terlec->id,
        'idNivel'  => $this->nivel->id,
        'idCurPlan'=> $this->curplan->id,
        'cursec'   => '1er Año A',
        'c'        => 'A',
        's'        => null,
        'turno'    => 'Mañana',
        'orden'    => null,
    ]);

    expect($curso)->toBeInstanceOf(Curso::class);

    $materias = Materia::where('idCursos', $curso->Id)->get();
    expect($materias)->toHaveCount(3);

    // Verifica que idMatPlan está correctamente vinculado
    $materias->each(function ($m) {
        expect($m->idMatPlan)->toBeIn($this->matplanes->pluck('id')->toArray());
    });
});

it('valida que el curplan tenga materias antes de generar', function () {
    $vacio = CurPlan::factory()->create(['idPlan' => \App\Models\Core\Plan::factory()->create(['idNivel' => $this->nivel->id])->id]);

    expect($this->servicio->validarCurPlan($vacio->id))->toBeFalse();
    expect($this->servicio->validarCurPlan($this->curplan->id))->toBeTrue();
});

it('las materias generadas preservan el nombre del matplan', function () {
    $matplan = $this->matplanes->first();
    $matplan->update(['matPlanMateria' => 'Matemática']);

    $curso = $this->servicio->generar([
        'idTerlec' => $this->terlec->id,
        'idNivel'  => $this->nivel->id,
        'idCurPlan'=> $this->curplan->id,
        'cursec'   => '1er Año A',
    ]);

    $materia = Materia::where('idCursos', $curso->Id)
        ->where('idMatPlan', $matplan->id)
        ->first();

    expect($materia->materia)->toBe('Matemática');
});
