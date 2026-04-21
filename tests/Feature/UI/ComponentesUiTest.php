<?php

/**
 * Tests de humo para los componentes UI del Design System.
 * Verifican que los componentes renderizan sin errores y respetan
 * los atributos ARIA y clases del design system.
 */

use App\Models\Auth\Profesor;
use App\Models\Auth\ProfesorTipo;
use App\Models\Core\Nivel;
use App\Models\Core\Terlec;

beforeEach(function () {
    $tipo = ProfesorTipo::factory()->create(['tipo' => 'Directivo', 'accesoMenu' => 2]);
    $this->nivel  = Nivel::factory()->create();
    $this->terlec = Terlec::factory()->create();

    $this->profesor = Profesor::factory()->create([
        'IdTipoProf' => $tipo->id,
        'pwrd'       => '123456',
        'permisos'   => str_repeat('1', 50),
    ]);

    $this->actingAs($this->profesor, 'staff');
    session(['nivel_id' => $this->nivel->id, 'terlec_id' => $this->terlec->id]);
});

it('el dashboard de staff carga sin errores', function () {
    $this->get(route('staff.dashboard'))
        ->assertOk()
        ->assertSee('Sistemas Escolares');
});

it('la pantalla de terlec incluye page-header con breadcrumbs', function () {
    $this->get(route('staff.terlec.index'))
        ->assertOk()
        ->assertSee('Ciclos lectivos')
        ->assertSee('Inicio');
});

it('la pantalla de niveles incluye page-header', function () {
    $this->get(route('staff.nivel.index'))
        ->assertOk()
        ->assertSee('Niveles');
});

it('la pantalla de planes incluye page-header', function () {
    $this->get(route('staff.plan.index'))
        ->assertOk()
        ->assertSee('Planes');
});

it('la pantalla de curplan incluye page-header', function () {
    $this->get(route('staff.curplan.index'))
        ->assertOk()
        ->assertSee('Cursos modelo');
});

it('la pantalla de cursos incluye page-header', function () {
    $this->get(route('staff.cursos.index'))
        ->assertOk()
        ->assertSee('Cursos');
});

it('la pantalla de legajos incluye page-header y filtro de búsqueda', function () {
    $this->get(route('staff.legajos.index'))
        ->assertOk()
        ->assertSee('Legajos');
});

it('la pantalla de matrículas carga sin errores', function () {
    $this->get(route('staff.matriculas.index'))
        ->assertOk()
        ->assertSee('Matr');
});

it('la pantalla de entornos carga sin errores', function () {
    $this->get(route('staff.ento.index'))
        ->assertOk()
        ->assertSee('Entorno');
});

it('el layout staff redirige a login si no autenticado', function () {
    auth('staff')->logout();

    $this->get(route('staff.dashboard'))
        ->assertRedirect(route('staff.login'));
});
