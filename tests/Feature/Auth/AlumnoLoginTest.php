<?php

use App\Models\Core\Legajo;
use App\Models\Core\Nivel;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

beforeEach(function () {
    $this->nivel = Nivel::factory()->create(['nivel' => 'Nivel Secundario', 'abrev' => 'Secu']);
});

it('muestra el formulario de login de alumno', function () {
    $this->get(route('alumno.login'))
        ->assertOk()
        ->assertSee('Autogestión de alumnos');
});

it('rechaza login de alumno con credenciales incorrectas', function () {
    $legajo = Legajo::factory()->create([
        'idnivel' => $this->nivel->id,
        'pwrd'    => '123456',
    ]);

    Livewire::test(\App\Livewire\Auth\AlumnoLoginForm::class)
        ->set('dni', $legajo->dni)
        ->set('password', 'wrong')
        ->call('login')
        ->assertHasErrors('dni');
});

it('autentica alumno con contraseña plain text', function () {
    $legajo = Legajo::factory()->create([
        'idnivel' => $this->nivel->id,
        'pwrd'    => '123456',
    ]);

    Livewire::test(\App\Livewire\Auth\AlumnoLoginForm::class)
        ->set('dni', (string) $legajo->dni)
        ->set('password', '123456')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('alumno.dashboard'));
});

it('middleware protege las rutas de alumno', function () {
    $this->get(route('alumno.dashboard'))
        ->assertRedirect(route('alumno.login'));
});

it('rehashea la contraseña del alumno al hacer login', function () {
    $legajo = Legajo::factory()->create([
        'idnivel' => $this->nivel->id,
        'pwrd'    => '123456',
    ]);

    Livewire::test(\App\Livewire\Auth\AlumnoLoginForm::class)
        ->set('dni', (string) $legajo->dni)
        ->set('password', '123456')
        ->call('login');

    $refreshed = $legajo->fresh();
    expect(Hash::check('123456', $refreshed->pwrd))->toBeTrue();
});
