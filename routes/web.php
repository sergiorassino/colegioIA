<?php

use App\Livewire\Auth\AlumnoLoginForm;
use App\Livewire\Auth\StaffLoginForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirección raíz
Route::get('/', function () {
    return redirect()->route('staff.login');
});

// ─────────────────────────────────────────────────────────────
//  Auth — Staff (profesores, directivos, admin)
// ─────────────────────────────────────────────────────────────
Route::prefix('staff')->name('staff.')->group(function () {

    // Login / logout (sin middleware de auth)
    Route::get('/login', StaffLoginForm::class)->name('login');
    Route::post('/logout', function () {
        Auth::guard('staff')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('staff.login');
    })->name('logout');

    // Área protegida
    Route::middleware(['staff', 'staff.ctx'])->group(function () {
        Route::get('/dashboard', function () {
            return view('staff.dashboard')->layout('layouts.staff');
        })->name('dashboard');

        // ── Parametrización ──────────────────────────────────────
        Route::get('/ciclos',    \App\Livewire\Core\Terlec\TerlecIndex::class)->name('terlec.index');
        Route::get('/niveles',   \App\Livewire\Core\Nivel\NivelIndex::class)->name('nivel.index');
        Route::get('/planes',    \App\Livewire\Core\Plan\PlanIndex::class)->name('plan.index');
        Route::get('/curplanes', \App\Livewire\Core\CurPlan\CurPlanIndex::class)->name('curplan.index');
        Route::get('/entorno',   \App\Livewire\Core\Ento\EntoIndex::class)->name('ento.index');

        // ── Cursos del año ───────────────────────────────────────
        Route::get('/cursos', \App\Livewire\Core\Cursos\CursoIndex::class)->name('cursos.index');

        // ── Legajos ──────────────────────────────────────────────
        Route::get('/legajos',          \App\Livewire\Core\Legajos\LegajoIndex::class)->name('legajos.index');
        Route::get('/legajos/crear',    \App\Livewire\Core\Legajos\LegajoForm::class)->name('legajos.crear');
        Route::get('/legajos/{id}',     \App\Livewire\Core\Legajos\LegajoForm::class)->name('legajos.editar');

        // ── Matriculación ────────────────────────────────────────
        Route::get('/matriculas', \App\Livewire\Core\Matriculas\MatriculaIndex::class)->name('matriculas.index');

        // ── Permisos (requiere permiso 0 — administración) ───────
        Route::get('/permisos', function () {
            abort(404); // Módulo placeholder — etapa futura
        })->name('permisos');
    });
});

// ─────────────────────────────────────────────────────────────
//  Auth — Alumnos / Familias (autogestión)
// ─────────────────────────────────────────────────────────────
Route::prefix('alumno')->name('alumno.')->group(function () {

    Route::get('/login', AlumnoLoginForm::class)->name('login');
    Route::post('/logout', function () {
        Auth::guard('alumno')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('alumno.login');
    })->name('logout');

    Route::middleware(['alumno'])->group(function () {
        Route::get('/dashboard', function () {
            return view('alumno.dashboard')->layout('layouts.autogestion');
        })->name('dashboard');

        Route::get('/calificaciones', function () {
            return view('alumno.calificaciones')->layout('layouts.autogestion');
        })->name('calificaciones');

        Route::get('/materias', function () {
            return view('alumno.materias')->layout('layouts.autogestion');
        })->name('materias');

        Route::get('/perfil', function () {
            return view('alumno.perfil')->layout('layouts.autogestion');
        })->name('perfil');
    });
});
