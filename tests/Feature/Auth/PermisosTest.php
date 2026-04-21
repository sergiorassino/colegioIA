<?php

use App\Auth\PermisoSlugMap;
use App\Auth\PermisosService;
use App\Models\Auth\Profesor;
use App\Models\Auth\ProfesorTipo;
use App\Models\Core\Nivel;
use App\Models\Core\Terlec;
use Illuminate\Support\Facades\Gate;

beforeEach(function () {
    $this->tipo  = ProfesorTipo::factory()->create(['tipo' => 'Directivo', 'accesoMenu' => 2]);
    $this->nivel = Nivel::factory()->create();
    Terlec::factory()->create();
});

it('tienePermiso devuelve true para la posición correcta', function () {
    $profesor = Profesor::factory()->create([
        'IdTipoProf' => $this->tipo->id,
        'permisos'   => '1' . str_repeat('0', 49),
    ]);

    expect($profesor->tienePermiso(0))->toBeTrue();
    expect($profesor->tienePermiso(1))->toBeFalse();
});

it('esProfesor devuelve true solo para el tipo Profesor/a', function () {
    $tipoProf = ProfesorTipo::factory()->create(['tipo' => 'Profesor/a', 'accesoMenu' => 6]);
    $profesor = Profesor::factory()->create(['IdTipoProf' => $tipoProf->id]);

    expect($profesor->esProfesor())->toBeTrue();

    $director = Profesor::factory()->create(['IdTipoProf' => $this->tipo->id]);
    expect($director->esProfesor())->toBeFalse();
});

it('staff con permisos completos puede acceder a ruta protegida', function () {
    $profesor = Profesor::factory()->create([
        'IdTipoProf' => $this->tipo->id,
        'pwrd'       => '123456',
        'permisos'   => str_repeat('1', 50),
    ]);

    $this->actingAs($profesor, 'staff')
        ->withSession(['nivel_id' => $this->nivel->id, 'terlec_id' => Terlec::first()->id])
        ->get(route('staff.dashboard'))
        ->assertOk();
});
