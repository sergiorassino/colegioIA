<?php

use App\Livewire\Core\Terlec\TerlecIndex;
use App\Models\Auth\Profesor;
use App\Models\Auth\ProfesorTipo;
use App\Models\Core\Nivel;
use App\Models\Core\Terlec;
use Livewire\Livewire;

beforeEach(function () {
    $tipo = ProfesorTipo::factory()->create(['tipo' => 'Directivo', 'accesoMenu' => 2]);
    $this->nivel  = Nivel::factory()->create();
    $this->terlec = Terlec::factory()->create(['ano' => 2026, 'orden' => 1]);

    $this->profesor = Profesor::factory()->create([
        'IdTipoProf' => $tipo->id,
        'pwrd'       => '123456',
        'permisos'   => str_repeat('1', 50),
    ]);

    $this->actingAs($this->profesor, 'staff');
    session(['nivel_id' => $this->nivel->id, 'terlec_id' => $this->terlec->id]);
});

it('muestra la lista de ciclos lectivos', function () {
    Livewire::test(TerlecIndex::class)
        ->assertSee(2026);
});

it('crea un nuevo ciclo lectivo', function () {
    Livewire::test(TerlecIndex::class)
        ->call('crear')
        ->set('ano', 2027)
        ->set('orden', 0)
        ->call('guardar')
        ->assertHasNoErrors()
        ->assertSee('Ciclo lectivo creado');

    expect(Terlec::where('ano', 2027)->exists())->toBeTrue();
});

it('valida el año requerido', function () {
    Livewire::test(TerlecIndex::class)
        ->call('crear')
        ->set('ano', null)
        ->set('orden', 1)
        ->call('guardar')
        ->assertHasErrors('ano');
});

it('edita un ciclo lectivo existente', function () {
    Livewire::test(TerlecIndex::class)
        ->call('editar', $this->terlec->id)
        ->set('ano', 2030)
        ->call('guardar')
        ->assertHasNoErrors();

    expect($this->terlec->fresh()->ano)->toBe(2030);
});

it('impide borrar ciclo con datos asociados', function () {
    // Crear un curso que referencia el terlec
    \App\Models\Core\Curso::factory()->create([
        'idTerlec'  => $this->terlec->id,
        'idNivel'   => $this->nivel->id,
        'idCurPlan' => \App\Models\Core\CurPlan::factory()->create()->id,
    ]);

    Livewire::test(TerlecIndex::class)
        ->call('confirmarBorrar', $this->terlec->id)
        ->call('borrar')
        ->assertSee('No se puede eliminar');

    expect(Terlec::find($this->terlec->id))->not->toBeNull();
});
