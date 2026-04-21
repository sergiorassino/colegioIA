<?php

use App\Livewire\Core\CurPlan\CurPlanIndex;
use App\Models\Auth\Profesor;
use App\Models\Auth\ProfesorTipo;
use App\Models\Core\CurPlan;
use App\Models\Core\MatPlan;
use App\Models\Core\Nivel;
use App\Models\Core\Plan;
use App\Models\Core\Terlec;
use Livewire\Livewire;

beforeEach(function () {
    $tipo = ProfesorTipo::factory()->create(['tipo' => 'Directivo', 'accesoMenu' => 2]);
    $this->nivel  = Nivel::factory()->create(['nivel' => 'Secundario']);
    $this->terlec = Terlec::factory()->create(['ano' => 2026, 'orden' => 1]);
    $this->plan   = Plan::factory()->create(['idNivel' => $this->nivel->id]);
    $this->curPlan = CurPlan::factory()->create(['idPlan' => $this->plan->id, 'curPlanCurso' => '1er Año']);

    $this->profesor = Profesor::factory()->create([
        'IdTipoProf' => $tipo->id,
        'pwrd'       => '123456',
        'permisos'   => str_repeat('1', 50),
    ]);

    $this->actingAs($this->profesor, 'staff');
    session(['nivel_id' => $this->nivel->id, 'terlec_id' => $this->terlec->id]);
});

it('renderiza la pantalla de curplan sin errores', function () {
    Livewire::test(CurPlanIndex::class)
        ->assertStatus(200)
        ->assertSee('1er A');
});

it('crea un curso modelo nuevo', function () {
    Livewire::test(CurPlanIndex::class)
        ->call('crearCurPlan')
        ->set('idPlan', $this->plan->id)
        ->set('curPlanCurso', '2do Año')
        ->call('guardarCurPlan')
        ->assertHasNoErrors();

    expect(CurPlan::where('curPlanCurso', '2do Año')->exists())->toBeTrue();
});

it('valida campos requeridos al guardar curplan', function () {
    Livewire::test(CurPlanIndex::class)
        ->call('crearCurPlan')
        ->set('idPlan', null)
        ->set('curPlanCurso', '')
        ->call('guardarCurPlan')
        ->assertHasErrors(['idPlan', 'curPlanCurso']);
});

it('selecciona un curso modelo y muestra las materias', function () {
    MatPlan::factory()->create([
        'idCurPlan'      => $this->curPlan->id,
        'matPlanMateria' => 'Matemática',
        'ord'            => 1,
    ]);

    Livewire::test(CurPlanIndex::class)
        ->call('seleccionarCurPlan', $this->curPlan->id)
        ->assertSet('selectedCurPlanId', $this->curPlan->id)
        ->assertSee('Matemática');
});

it('agrega una materia modelo al curso', function () {
    Livewire::test(CurPlanIndex::class)
        ->call('seleccionarCurPlan', $this->curPlan->id)
        ->call('crearMatPlan')
        ->set('matPlanMateria', 'Historia')
        ->set('matPlanOrd', 1)
        ->call('guardarMatPlan')
        ->assertHasNoErrors();

    expect(MatPlan::where('matPlanMateria', 'Historia')->where('idCurPlan', $this->curPlan->id)->exists())->toBeTrue();
});
