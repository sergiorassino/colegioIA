<?php

use App\Livewire\Core\Ento\EntoIndex;
use App\Models\Auth\Profesor;
use App\Models\Auth\ProfesorTipo;
use App\Models\Core\Ento;
use App\Models\Core\Nivel;
use App\Models\Core\Terlec;
use Livewire\Livewire;

beforeEach(function () {
    $tipo = ProfesorTipo::factory()->create(['tipo' => 'Directivo', 'accesoMenu' => 2]);
    $this->nivel  = Nivel::factory()->create(['nivel' => 'Primario']);
    $this->terlec = Terlec::factory()->create(['ano' => 2026, 'orden' => 1]);

    $this->ento = Ento::create([
        'idNivel'          => $this->nivel->id,
        'idTerlecVerNotas' => $this->terlec->id,
        'insti'            => 'Escuela Test',
        'platOff'          => 0,
        'verNotasOff'      => 0,
    ]);

    $this->profesor = Profesor::factory()->create([
        'IdTipoProf' => $tipo->id,
        'pwrd'       => '123456',
        'permisos'   => str_repeat('1', 50),
    ]);

    $this->actingAs($this->profesor, 'staff');
    session(['nivel_id' => $this->nivel->id, 'terlec_id' => $this->terlec->id]);
});

it('renderiza la pantalla de entornos sin errores', function () {
    Livewire::test(EntoIndex::class)
        ->assertStatus(200)
        ->assertSee('Escuela Test');
});

it('puede editar el entorno de un nivel', function () {
    $id = $this->ento->getKey();
    Livewire::test(EntoIndex::class)
        ->call('editar', $id)
        ->assertSet('showForm', true)
        ->assertSet('editId', $id)
        ->assertSet('insti', 'Escuela Test');
});

it('guarda cambios en el entorno correctamente', function () {
    $id = $this->ento->getKey();
    Livewire::test(EntoIndex::class)
        ->call('editar', $id)
        ->set('insti', 'Nueva Escuela')
        ->set('idNivel', $this->nivel->id)
        ->set('idTerlecVerNotas', $this->terlec->id)
        ->call('guardar')
        ->assertHasNoErrors();

    expect($this->ento->fresh()->insti)->toBe('Nueva Escuela');
});

it('valida que idNivel sea requerido al guardar', function () {
    $id = $this->ento->getKey();
    Livewire::test(EntoIndex::class)
        ->call('editar', $id)
        ->set('idNivel', null)
        ->call('guardar')
        ->assertHasErrors('idNivel');
});

it('cancela la edición del entorno', function () {
    $id = $this->ento->getKey();
    Livewire::test(EntoIndex::class)
        ->call('editar', $id)
        ->assertSet('showForm', true)
        ->call('cancelar')
        ->assertSet('showForm', false);
});
