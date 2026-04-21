<?php

use App\Livewire\Core\Nivel\NivelIndex;
use App\Models\Auth\Profesor;
use App\Models\Auth\ProfesorTipo;
use App\Models\Core\Nivel;
use App\Models\Core\Terlec;
use Livewire\Livewire;

beforeEach(function () {
    $tipo = ProfesorTipo::factory()->create(['tipo' => 'Directivo', 'accesoMenu' => 2]);
    $this->nivel  = Nivel::factory()->create(['nivel' => 'Primario', 'abrev' => 'PRI']);
    $this->terlec = Terlec::factory()->create(['ano' => 2026, 'orden' => 1]);

    $this->profesor = Profesor::factory()->create([
        'IdTipoProf' => $tipo->id,
        'pwrd'       => '123456',
        'permisos'   => str_repeat('1', 50),
    ]);

    $this->actingAs($this->profesor, 'staff');
    session(['nivel_id' => $this->nivel->id, 'terlec_id' => $this->terlec->id]);
});

it('renderiza la pantalla de niveles sin errores', function () {
    Livewire::test(NivelIndex::class)
        ->assertStatus(200)
        ->assertSee('Primario');
});

it('crea un nivel nuevo correctamente', function () {
    Livewire::test(NivelIndex::class)
        ->call('crear')
        ->set('nivel', 'Secundario')
        ->set('abrev', 'SEC')
        ->call('guardar')
        ->assertHasNoErrors();

    expect(Nivel::where('nivel', 'Secundario')->exists())->toBeTrue();
});

it('valida el campo nivel como requerido', function () {
    Livewire::test(NivelIndex::class)
        ->call('crear')
        ->set('nivel', '')
        ->call('guardar')
        ->assertHasErrors('nivel');
});

it('edita un nivel existente', function () {
    Livewire::test(NivelIndex::class)
        ->call('editar', $this->nivel->id)
        ->set('nivel', 'Inicial')
        ->call('guardar')
        ->assertHasNoErrors();

    expect($this->nivel->fresh()->nivel)->toBe('Inicial');
});

it('impide borrar nivel con registros asociados', function () {
    Nivel::factory()->create(['nivel' => 'Secundario']);
    // Crear plan asociado al nivel original
    \App\Models\Core\Plan::factory()->create(['idNivel' => $this->nivel->id]);

    Livewire::test(NivelIndex::class)
        ->call('confirmarBorrar', $this->nivel->id)
        ->call('borrar')
        ->assertSee('No se puede eliminar');

    expect(Nivel::find($this->nivel->id))->not->toBeNull();
});

it('cancela el formulario y lo oculta', function () {
    Livewire::test(NivelIndex::class)
        ->call('crear')
        ->assertSet('showForm', true)
        ->call('cancelar')
        ->assertSet('showForm', false);
});
