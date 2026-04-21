<?php

use App\Models\Core\Calificacion;
use App\Models\Core\Condicion;
use App\Models\Core\CurPlan;
use App\Models\Core\Curso;
use App\Models\Core\Ento;
use App\Models\Core\Legajo;
use App\Models\Core\Materia;
use App\Models\Core\MatPlan;
use App\Models\Core\Matricula;
use App\Models\Core\Nivel;
use App\Models\Core\Terlec;
use App\Models\Auth\LegajoAuth;

beforeEach(function () {
    $this->nivel  = Nivel::factory()->create(['nivel' => 'Secundario']);
    $this->terlec = Terlec::factory()->create(['ano' => 2026]);

    // Crear entorno para el nivel
    Ento::create([
        'idNivel'             => $this->nivel->id,
        'idTerlecVerNotas'    => $this->terlec->id,
        'insti'               => 'Colegio Test',
        'platOff'             => 0,
        'verNotasOff'         => 0,
    ]);

    $this->legajo = Legajo::factory()->create([
        'idnivel' => $this->nivel->id,
        'pwrd'    => '123456',
        'dni'     => 20000001,
    ]);
});

it('alumno autenticado puede ver su dashboard', function () {
    $alumno = LegajoAuth::find($this->legajo->id);
    $this->actingAs($alumno, 'alumno');

    $this->get(route('alumno.dashboard'))
        ->assertOk();
});

it('alumno no autenticado es redirigido al login', function () {
    $this->get(route('alumno.dashboard'))
        ->assertRedirect(route('alumno.login'));
});

it('alumno autenticado puede acceder a la ruta de calificaciones', function () {
    $alumno = LegajoAuth::find($this->legajo->id);
    $this->actingAs($alumno, 'alumno');

    $this->get(route('alumno.calificaciones'))
        ->assertOk();
});

it('alumno no autenticado no puede acceder a calificaciones', function () {
    $this->get(route('alumno.calificaciones'))
        ->assertRedirect(route('alumno.login'));
});
