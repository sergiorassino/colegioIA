<?php

namespace App\Livewire\Core\Cursos;

use App\Models\Core\CurPlan;
use App\Models\Core\Curso;
use App\Models\Core\Materia;
use App\Models\Core\Nivel;
use App\Models\Core\Terlec;
use App\Services\GenerarCursoService;
use App\Services\MateriaIntegridadService;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CursoIndex extends Component
{
    // ── Filtros ───────────────────────────────────────────────
    public ?int $filtroTerlec = null;
    public ?int $filtroNivel  = null;

    // ── Formulario nuevo curso ────────────────────────────────
    public bool $showCursoForm = false;
    public ?int $editCursoId   = null;

    #[Rule(['required', 'integer', 'exists:terlec,id'])]
    public ?int $formTerlec = null;

    #[Rule(['required', 'integer', 'exists:niveles,id'])]
    public ?int $formNivel = null;

    #[Rule(['required', 'integer', 'exists:curplan,id'])]
    public ?int $formCurPlan = null;

    #[Rule(['required', 'string', 'max:30'])]
    public string $formCursec = '';

    #[Rule(['nullable', 'string', 'max:1'])]
    public ?string $formC = null;

    #[Rule(['nullable', 'string', 'max:1'])]
    public ?string $formS = null;

    #[Rule(['nullable', 'string', 'max:20'])]
    public ?string $formTurno = null;

    public string $confirmDeleteCursoId = '';

    // ── Materias inline ───────────────────────────────────────
    public ?int $selectedCursoId = null;

    public bool   $showMateriaForm = false;
    public ?int   $editMateriaId   = null;
    public string $formMateria     = '';
    public ?int   $formMateriaOrd  = null;
    public ?string $formMateriaAbrev = null;
    public string $confirmDeleteMateriaId = '';

    // ── Computed ─────────────────────────────────────────────

    #[Computed]
    public function niveles()
    {
        return Nivel::orderBy('nivel')->get();
    }

    #[Computed]
    public function terlecs()
    {
        return Terlec::orderBy('orden')->get();
    }

    #[Computed]
    public function curplanes()
    {
        $query = CurPlan::with('plan.nivel')->orderBy('idPlan')->orderBy('curPlanCurso');

        if ($this->formNivel) {
            $query->whereHas('plan', fn ($q) => $q->where('idNivel', $this->formNivel));
        }

        return $query->get();
    }

    #[Computed]
    public function cursos()
    {
        return Curso::with(['curPlan', 'terlec', 'nivel'])
            ->when($this->filtroTerlec, fn ($q) => $q->where('idTerlec', $this->filtroTerlec))
            ->when($this->filtroNivel,  fn ($q) => $q->where('idNivel', $this->filtroNivel))
            ->orderBy('idNivel')
            ->orderBy('cursec')
            ->get();
    }

    #[Computed]
    public function materias()
    {
        if (! $this->selectedCursoId) {
            return collect();
        }

        return Materia::where('idCursos', $this->selectedCursoId)
            ->orderBy('ord')
            ->get();
    }

    public function mount(): void
    {
        $this->filtroTerlec = session('terlec_id');
        $this->filtroNivel  = session('nivel_id');
    }

    // ── Curso actions ─────────────────────────────────────────

    public function crearCurso(): void
    {
        $this->reset(['editCursoId', 'formTerlec', 'formNivel', 'formCurPlan', 'formCursec', 'formC', 'formS', 'formTurno']);
        $this->formTerlec = session('terlec_id');
        $this->formNivel  = session('nivel_id');
        $this->showCursoForm = true;
    }

    public function editarCurso(int $id): void
    {
        $curso = Curso::findOrFail($id);
        $this->editCursoId   = $curso->Id;
        $this->formTerlec    = $curso->idTerlec;
        $this->formNivel     = $curso->idNivel;
        $this->formCurPlan   = $curso->idCurPlan;
        $this->formCursec    = $curso->cursec ?? '';
        $this->formC         = $curso->c;
        $this->formS         = $curso->s;
        $this->formTurno     = $curso->turno;
        $this->showCursoForm = true;
        unset($this->curplanes);
    }

    public function guardarCurso(GenerarCursoService $generarCursoService): void
    {
        $this->validate([
            'formTerlec'  => ['required', 'integer', 'exists:terlec,id'],
            'formNivel'   => ['required', 'integer', 'exists:niveles,id'],
            'formCurPlan' => ['required', 'integer', 'exists:curplan,id'],
            'formCursec'  => ['required', 'string', 'max:30'],
            'formC'       => ['nullable', 'string', 'max:1'],
            'formS'       => ['nullable', 'string', 'max:1'],
            'formTurno'   => ['nullable', 'string', 'max:20'],
        ]);

        if ($this->editCursoId) {
            // Solo edita metadatos del curso; materias se gestionan en la sección inline
            Curso::findOrFail($this->editCursoId)->update([
                'idTerlec'  => $this->formTerlec,
                'idNivel'   => $this->formNivel,
                'idCurPlan' => $this->formCurPlan,
                'cursec'    => $this->formCursec,
                'c'         => $this->formC,
                's'         => $this->formS,
                'turno'     => $this->formTurno,
            ]);
            session()->flash('success', 'Curso actualizado.');
        } else {
            if (! $generarCursoService->validarCurPlan($this->formCurPlan)) {
                session()->flash('error', 'El curso modelo seleccionado no tiene materias modelo. Agregue materias en el módulo "Cursos modelo" antes de continuar.');
                return;
            }

            $generarCursoService->generar([
                'idTerlec' => $this->formTerlec,
                'idNivel'  => $this->formNivel,
                'idCurPlan'=> $this->formCurPlan,
                'cursec'   => $this->formCursec,
                'c'        => $this->formC,
                's'        => $this->formS,
                'turno'    => $this->formTurno,
            ]);
            session()->flash('success', 'Curso creado con sus materias del año.');
        }

        $this->cancelarCurso();
        unset($this->cursos);
    }

    public function confirmarBorrarCurso(int $id): void
    {
        $this->confirmDeleteCursoId = (string) $id;
    }

    public function borrarCurso(): void
    {
        if (! $this->confirmDeleteCursoId) {
            return;
        }

        $curso = Curso::findOrFail((int) $this->confirmDeleteCursoId);

        if ($curso->matriculas()->exists()) {
            session()->flash('error', 'No se puede eliminar: el curso tiene matrículas asociadas.');
            $this->confirmDeleteCursoId = '';
            return;
        }

        // Eliminar calificaciones y materias del año
        $curso->calificaciones()->delete();
        $curso->materias()->delete();
        $curso->delete();

        if ($this->selectedCursoId === (int) $this->confirmDeleteCursoId) {
            $this->selectedCursoId = null;
        }

        $this->confirmDeleteCursoId = '';
        session()->flash('success', 'Curso y materias del año eliminados.');
        unset($this->cursos);
    }

    public function cancelarCurso(): void
    {
        $this->reset(['showCursoForm', 'editCursoId', 'formTerlec', 'formNivel', 'formCurPlan', 'formCursec', 'formC', 'formS', 'formTurno', 'confirmDeleteCursoId']);
        $this->resetErrorBag();
    }

    // ── Selección para ver materias ───────────────────────────

    public function seleccionarCurso(int $id): void
    {
        $this->selectedCursoId = ($this->selectedCursoId === $id) ? null : $id;
        $this->cancelarMateria();
        unset($this->materias);
    }

    // ── Materia inline actions ────────────────────────────────

    public function crearMateria(): void
    {
        $this->reset(['editMateriaId', 'formMateria', 'formMateriaOrd', 'formMateriaAbrev']);
        $next = Materia::where('idCursos', $this->selectedCursoId)->max('ord') ?? 0;
        $this->formMateriaOrd  = $next + 1;
        $this->showMateriaForm = true;
    }

    public function editarMateria(int $id): void
    {
        $mat = Materia::findOrFail($id);
        $this->editMateriaId   = $mat->id;
        $this->formMateria     = $mat->materia ?? '';
        $this->formMateriaOrd  = $mat->ord;
        $this->formMateriaAbrev= $mat->abrev;
        $this->showMateriaForm = true;
    }

    public function guardarMateria(MateriaIntegridadService $integridadService): void
    {
        $this->validate([
            'formMateria'     => ['required', 'string', 'max:70'],
            'formMateriaOrd'  => ['required', 'integer', 'min:1'],
            'formMateriaAbrev'=> ['nullable', 'string', 'max:5'],
        ]);

        if ($this->editMateriaId) {
            $integridadService->renombrar($this->editMateriaId, $this->formMateria);
            // Actualizar ord y abrev directamente (no requieren propagación)
            Materia::findOrFail($this->editMateriaId)->update([
                'ord'   => $this->formMateriaOrd,
                'abrev' => $this->formMateriaAbrev,
            ]);
            session()->flash('success', 'Materia actualizada.');
        } else {
            $curso = Curso::findOrFail($this->selectedCursoId);
            $integridadService->agregar(
                idCursos : $curso->Id,
                idCurPlan: $curso->idCurPlan,
                idNivel  : $curso->idNivel,
                idTerlec : $curso->idTerlec,
                nombre   : $this->formMateria,
                ord      : $this->formMateriaOrd,
                abrev    : $this->formMateriaAbrev,
            );
            session()->flash('success', 'Materia agregada.');
        }

        $this->cancelarMateria();
        unset($this->materias);
    }

    public function confirmarBorrarMateria(int $id): void
    {
        $this->confirmDeleteMateriaId = (string) $id;
    }

    public function borrarMateria(MateriaIntegridadService $integridadService): void
    {
        if (! $this->confirmDeleteMateriaId) {
            return;
        }

        try {
            $integridadService->eliminar((int) $this->confirmDeleteMateriaId);
            session()->flash('success', 'Materia eliminada.');
        } catch (\RuntimeException $e) {
            session()->flash('error', $e->getMessage());
        }

        $this->confirmDeleteMateriaId = '';
        unset($this->materias);
    }

    public function cancelarMateria(): void
    {
        $this->reset(['showMateriaForm', 'editMateriaId', 'formMateria', 'formMateriaOrd', 'formMateriaAbrev', 'confirmDeleteMateriaId']);
        $this->resetErrorBag(['formMateria', 'formMateriaOrd', 'formMateriaAbrev']);
    }

    public function render(): View
    {
        return view('livewire.core.cursos.index')
            ->layout('layouts.staff');
    }
}
