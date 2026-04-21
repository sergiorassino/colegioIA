<?php

use App\Models\Auth\Profesor;
use App\Models\Auth\ProfesorTipo;
use App\Models\Core\Nivel;
use App\Models\Core\Terlec;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

beforeEach(function () {
    // Crear datos mínimos requeridos
    $this->tipo   = ProfesorTipo::factory()->create(['tipo' => 'Directivo', 'accesoMenu' => 2]);
    $this->nivel  = Nivel::factory()->create(['nivel' => 'Nivel Secundario', 'abrev' => 'Secu']);
    $this->terlec = Terlec::factory()->create(['ano' => 2026, 'orden' => 1]);
});

it('muestra el formulario de login de staff', function () {
    $this->get(route('staff.login'))
        ->assertOk()
        ->assertSee('Acceso al sistema de gestión');
});

it('rechaza login con credenciales incorrectas', function () {
    $profesor = Profesor::factory()->create([
        'IdTipoProf' => $this->tipo->id,
        'pwrd'       => '123456',
    ]);

    Livewire::test(\App\Livewire\Auth\StaffLoginForm::class)
        ->set('dni', $profesor->dni)
        ->set('password', 'wrong')
        ->set('nivel_id', $this->nivel->id)
        ->set('terlec_id', $this->terlec->id)
        ->call('login')
        ->assertHasErrors('dni');
});

it('autentica con contraseña en texto plano legacy', function () {
    $profesor = Profesor::factory()->create([
        'IdTipoProf' => $this->tipo->id,
        'pwrd'       => '123456',
        'permisos'   => str_repeat('1', 50),
    ]);

    Livewire::test(\App\Livewire\Auth\StaffLoginForm::class)
        ->set('dni', $profesor->dni)
        ->set('password', '123456')
        ->set('nivel_id', $this->nivel->id)
        ->set('terlec_id', $this->terlec->id)
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('staff.dashboard'));
});

it('autentica con contraseña hasheada en bcrypt', function () {
    $profesor = Profesor::factory()->create([
        'IdTipoProf' => $this->tipo->id,
        'pwrd'       => Hash::make('miClave'),
        'permisos'   => str_repeat('1', 50),
    ]);

    Livewire::test(\App\Livewire\Auth\StaffLoginForm::class)
        ->set('dni', $profesor->dni)
        ->set('password', 'miClave')
        ->set('nivel_id', $this->nivel->id)
        ->set('terlec_id', $this->terlec->id)
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('staff.dashboard'));
});

it('rehashea la contraseña plain text al hacer login exitoso', function () {
    $profesor = Profesor::factory()->create([
        'IdTipoProf' => $this->tipo->id,
        'pwrd'       => '123456',
        'permisos'   => str_repeat('1', 50),
    ]);

    Livewire::test(\App\Livewire\Auth\StaffLoginForm::class)
        ->set('dni', $profesor->dni)
        ->set('password', '123456')
        ->set('nivel_id', $this->nivel->id)
        ->set('terlec_id', $this->terlec->id)
        ->call('login');

    $refreshed = $profesor->fresh();
    expect(Hash::check('123456', $refreshed->pwrd))->toBeTrue();
});

it('guarda nivel_id y terlec_id en sesión al hacer login', function () {
    $profesor = Profesor::factory()->create([
        'IdTipoProf' => $this->tipo->id,
        'pwrd'       => '123456',
        'permisos'   => str_repeat('1', 50),
    ]);

    Livewire::test(\App\Livewire\Auth\StaffLoginForm::class)
        ->set('dni', $profesor->dni)
        ->set('password', '123456')
        ->set('nivel_id', $this->nivel->id)
        ->set('terlec_id', $this->terlec->id)
        ->call('login');

    expect(session('nivel_id'))->toBe($this->nivel->id);
    expect(session('terlec_id'))->toBe($this->terlec->id);
});

it('middleware protege las rutas de staff', function () {
    $this->get(route('staff.dashboard'))
        ->assertRedirect(route('staff.login'));
});
