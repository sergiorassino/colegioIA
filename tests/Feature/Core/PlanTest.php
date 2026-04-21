<?php

use App\Livewire\Core\Plan\PlanIndex;
use App\Models\Auth\Profesor;
use App\Models\Auth\ProfesorTipo;
use App\Models\Core\Nivel;
use App\Models\Core\Plan;
use App\Models\Core\Terlec;
use Livewire\Livewire;

beforeEach(function () {
    $tipo = ProfesorTipo::factory()->create(['tipo' => 'Directivo', 'accesoMenu' => 2]);
    $this->nivel  = Nivel::factory()->create(['nivel' => 'Secundario']);
    $this->terlec = Terlec::factory()->create(['ano' => 2026, 'orden' => 1]);
    $this->plan   = Plan::factory()->create(['idNivel' => $this->nivel->id, 'plan' => 'Plan A']);

    $this->profesor = Profesor::factory()->create([
        'IdTipoProf' => $tipo->id,
        'pwrd'       => '123456',
        'permisos'   => str_repeat('1', 50),
    ]);

    $this->actingAs($this->profesor, 'staff');
    session(['nivel_id' => $this->nivel->id, 'terlec_id' => $this->terlec->id]);
});

it('renderiza la pantalla de planes sin errores', function () {
    Livewire::test(PlanIndex::class)
        ->assertStatus(200)
        ->assertSee('Plan A');
});

it('crea un plan nuevo correctamente', function () {
    Livewire::test(PlanIndex::class)
        ->call('crear')
        ->set('idNivel', $this->nivel->id)
        ->set('plan', 'Plan B')
        ->set('abrev', 'B')
        ->call('guardar')
        ->assertHasNoErrors();

    expect(Plan::where('plan', 'Plan B')->exists())->toBeTrue();
});

it('valida campos requeridos al guardar', function () {
    Livewire::test(PlanIndex::class)
        ->call('crear')
        ->set('idNivel', null)
        ->set('plan', '')
        ->call('guardar')
        ->assertHasErrors(['idNivel', 'plan']);
});

it('edita un plan existente', function () {
    Livewire::test(PlanIndex::class)
        ->call('editar', $this->plan->id)
        ->set('plan', 'Plan Modificado')
        ->call('guardar')
        ->assertHasNoErrors();

    expect($this->plan->fresh()->plan)->toBe('Plan Modificado');
});

it('cancela el formulario de plan', function () {
    Livewire::test(PlanIndex::class)
        ->call('crear')
        ->assertSet('showForm', true)
        ->call('cancelar')
        ->assertSet('showForm', false);
});
