<?php

use App\Livewire\Core\Legajos\LegajoForm;
use App\Livewire\Core\Legajos\LegajoIndex;
use App\Models\Auth\Profesor;
use App\Models\Auth\ProfesorTipo;
use App\Models\Core\Legajo;
use App\Models\Core\Nivel;
use App\Models\Core\Terlec;
use Livewire\Livewire;

beforeEach(function () {
    $tipo = ProfesorTipo::factory()->create(['tipo' => 'Directivo', 'accesoMenu' => 2]);
    $this->nivel  = Nivel::factory()->create(['nivel' => 'Secundario', 'abrev' => 'Secu']);
    $this->terlec = Terlec::factory()->create(['ano' => 2026, 'orden' => 1]);

    $this->profesor = Profesor::factory()->create([
        'IdTipoProf' => $tipo->id,
        'pwrd'       => '123456',
        'permisos'   => str_repeat('1', 50),
    ]);

    $this->actingAs($this->profesor, 'staff');
    session(['nivel_id' => $this->nivel->id, 'terlec_id' => $this->terlec->id]);

    $this->legajo = Legajo::factory()->create([
        'apellido' => 'García',
        'nombre'   => 'Juan',
        'dni'      => 12345678,
        'idnivel'  => $this->nivel->id,
    ]);
});

it('lista legajos correctamente', function () {
    Livewire::test(LegajoIndex::class)
        ->assertSee('García');
});

it('busca legajos por apellido', function () {
    Legajo::factory()->create(['apellido' => 'López', 'idnivel' => $this->nivel->id]);

    Livewire::test(LegajoIndex::class)
        ->set('search', 'García')
        ->assertSee('García')
        ->assertDontSee('López');
});

it('muestra el formulario de edición de legajo', function () {
    Livewire::test(LegajoForm::class, ['id' => $this->legajo->id])
        ->assertSee('García');
});

it('guarda cambios en el legajo', function () {
    Livewire::test(LegajoForm::class, ['id' => $this->legajo->id])
        ->set('apellido', 'González')
        ->call('guardar')
        ->assertHasNoErrors();

    expect($this->legajo->fresh()->apellido)->toBe('González');
});

it('valida campos requeridos al guardar legajo', function () {
    Livewire::test(LegajoForm::class, ['id' => $this->legajo->id])
        ->set('apellido', '')
        ->set('nombre', '')
        ->call('guardar')
        ->assertHasErrors(['apellido', 'nombre']);
});

it('crea un nuevo legajo', function () {
    $nivelId = $this->nivel->id;

    Livewire::test(LegajoForm::class)
        ->set('apellido', 'Martínez')
        ->set('nombre', 'Ana')
        ->set('dni', 99887766)
        ->set('idnivel', $nivelId)
        ->call('guardar')
        ->assertHasNoErrors();

    expect(Legajo::where('dni', 99887766)->exists())->toBeTrue();
});
