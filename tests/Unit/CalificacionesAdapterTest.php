<?php

use App\Models\Core\Calificacion;
use App\Services\CalificacionesAdapter;

beforeEach(function () {
    $this->adapter = new CalificacionesAdapter();
});

function makeCalif(array $attrs = []): Calificacion
{
    $model = new Calificacion();
    foreach (array_merge([
        'ic01' => '', 'ic02' => '', 'ic03' => '', 'ic04' => '',
        'tm1' => '', 'tm2' => '', 'dic' => '', 'feb' => '',
        'apro' => null,
    ], $attrs) as $k => $v) {
        $model->$k = $v;
    }
    return $model;
}

it('devuelve 3 periodos para Inicial', function () {
    $c = makeCalif(['ic01' => 'EP', 'ic02' => 'EA', 'ic03' => 'EI']);
    $periodos = $this->adapter->periodos($c, CalificacionesAdapter::NIVEL_INICIAL);

    expect($periodos)->toHaveCount(3);
    expect($periodos[0]['campo'])->toBe('ic01');
    expect($periodos[0]['etiqueta'])->toBe('1° Trimestre');
    expect($periodos[0]['valor'])->toBe('EP');
});

it('devuelve 8 periodos para Primario', function () {
    $c = makeCalif();
    $periodos = $this->adapter->periodos($c, CalificacionesAdapter::NIVEL_PRIMARIO);
    expect($periodos)->toHaveCount(8);
});

it('devuelve 6 periodos para Secundario', function () {
    $c = makeCalif();
    $periodos = $this->adapter->periodos($c, CalificacionesAdapter::NIVEL_SECUNDARIO);
    expect($periodos)->toHaveCount(6);
});

it('detecta materia aprobada cuando apro=1', function () {
    $c = makeCalif(['apro' => 1]);
    expect($this->adapter->estaAprobada($c, CalificacionesAdapter::NIVEL_SECUNDARIO))->toBeTrue();
});

it('detecta materia aprobada con nota anual >= 6 en secundario', function () {
    $c = makeCalif(['tm1' => '7']);
    expect($this->adapter->estaAprobada($c, CalificacionesAdapter::NIVEL_SECUNDARIO))->toBeTrue();
});

it('detecta materia desaprobada con nota anual < 6', function () {
    $c = makeCalif(['tm1' => '4']);
    expect($this->adapter->estaAprobada($c, CalificacionesAdapter::NIVEL_SECUNDARIO))->toBeFalse();
});

it('estado devuelve Cursando si sin notas', function () {
    $c = makeCalif();
    expect($this->adapter->estado($c, CalificacionesAdapter::NIVEL_SECUNDARIO))->toBe('Cursando');
});

it('estado devuelve Aprobada cuando apro=1', function () {
    $c = makeCalif(['apro' => 1]);
    expect($this->adapter->estado($c, CalificacionesAdapter::NIVEL_SECUNDARIO))->toBe('Aprobada');
});

it('estado devuelve Pendiente Feb con diciembre cargado', function () {
    $c = makeCalif(['dic' => '4']);
    expect($this->adapter->estado($c, CalificacionesAdapter::NIVEL_SECUNDARIO))->toBe('Pendiente Feb');
});

it('estado devuelve Aprobada (Feb) con nota de febrero >= 4', function () {
    $c = makeCalif(['feb' => '6']);
    expect($this->adapter->estado($c, CalificacionesAdapter::NIVEL_SECUNDARIO))->toBe('Aprobada (Feb)');
});
