<?php

namespace App\Livewire\Core\CurPlan;

use App\Models\Core\CurPlan;
use App\Models\Core\MatPlan;
use App\Models\Core\Plan;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CurPlanIndex extends Component
{
    // ── CurPlan form ─────────────────────────────────────────
    public bool   $showCurPlanForm = false;
    public ?int   $editCurPlanId   = null;

    #[Rule(['required', 'integer', 'exists:planes,id'])]
    public ?int $idPlan = null;

    #[Rule(['required', 'string', 'max:100'])]
    public string $curPlanCurso = '';

    public string $confirmDeleteCurPlanId = '';

    // ── MatPlan inline ────────────────────────────────────────
    public ?int   $selectedCurPlanId  = null;
    public bool   $showMatPlanForm    = false;
    public ?int   $editMatPlanId      = null;

    #[Rule(['required', 'string', 'max:200'])]
    public string $matPlanMateria = '';

    #[Rule(['required', 'integer', 'min:1'])]
    public ?int $matPlanOrd = null;

    #[Rule(['nullable', 'string', 'max:10'])]
    public ?string $matPlanAbrev = null;

    public string $confirmDeleteMatPlanId = '';

    // ── Computed ─────────────────────────────────────────────

    #[Computed]
    public function planes()
    {
        return Plan::with('nivel')->orderBy('idNivel')->orderBy('plan')->get();
    }

    #[Computed]
    public function curplanes()
    {
        return CurPlan::with(['plan.nivel'])->orderBy('idPlan')->orderBy('curPlanCurso')->get();
    }

    #[Computed]
    public function matplanes()
    {
        if (! $this->selectedCurPlanId) {
            return collect();
        }

        return MatPlan::where('idCurPlan', $this->selectedCurPlanId)
            ->orderBy('ord')
            ->get();
    }

    // ── CurPlan actions ───────────────────────────────────────

    public function crearCurPlan(): void
    {
        $this->reset(['editCurPlanId', 'idPlan', 'curPlanCurso']);
        $this->showCurPlanForm = true;
    }

    public function editarCurPlan(int $id): void
    {
        $curplan = CurPlan::findOrFail($id);
        $this->editCurPlanId  = $curplan->id;
        $this->idPlan         = $curplan->idPlan;
        $this->curPlanCurso   = $curplan->curPlanCurso;
        $this->showCurPlanForm = true;
    }

    public function guardarCurPlan(): void
    {
        $this->validateOnly('idPlan', ['idPlan' => ['required', 'integer', 'exists:planes,id']]);
        $this->validateOnly('curPlanCurso', ['curPlanCurso' => ['required', 'string', 'max:100']]);

        if ($this->editCurPlanId) {
            CurPlan::findOrFail($this->editCurPlanId)->update([
                'idPlan'       => $this->idPlan,
                'curPlanCurso' => $this->curPlanCurso,
            ]);
            session()->flash('success', 'Curso modelo actualizado.');
        } else {
            CurPlan::create([
                'idPlan'       => $this->idPlan,
                'curPlanCurso' => $this->curPlanCurso,
            ]);
            session()->flash('success', 'Curso modelo creado.');
        }

        $this->cancelarCurPlan();
        unset($this->curplanes);
    }

    public function confirmarBorrarCurPlan(int $id): void
    {
        $this->confirmDeleteCurPlanId = (string) $id;
    }

    public function borrarCurPlan(): void
    {
        if (! $this->confirmDeleteCurPlanId) {
            return;
        }

        $curplan = CurPlan::findOrFail((int) $this->confirmDeleteCurPlanId);

        if ($curplan->cursos()->exists()) {
            session()->flash('error', 'No se puede eliminar: el curso modelo tiene cursos del año asociados.');
            $this->confirmDeleteCurPlanId = '';
            return;
        }

        $curplan->matplanes()->delete();
        $curplan->delete();
        $this->confirmDeleteCurPlanId = '';

        if ($this->selectedCurPlanId === (int) $this->confirmDeleteCurPlanId) {
            $this->selectedCurPlanId = null;
        }

        session()->flash('success', 'Curso modelo eliminado.');
        unset($this->curplanes);
    }

    public function cancelarCurPlan(): void
    {
        $this->reset(['showCurPlanForm', 'editCurPlanId', 'idPlan', 'curPlanCurso', 'confirmDeleteCurPlanId']);
        $this->resetErrorBag(['idPlan', 'curPlanCurso']);
    }

    // ── Selección para ver MatPlanes ──────────────────────────

    public function seleccionarCurPlan(int $id): void
    {
        $this->selectedCurPlanId = ($this->selectedCurPlanId === $id) ? null : $id;
        $this->cancelarMatPlan();
        unset($this->matplanes);
    }

    // ── MatPlan actions ───────────────────────────────────────

    public function crearMatPlan(): void
    {
        $this->reset(['editMatPlanId', 'matPlanMateria', 'matPlanOrd', 'matPlanAbrev']);
        $nextOrd = MatPlan::where('idCurPlan', $this->selectedCurPlanId)->max('ord') ?? 0;
        $this->matPlanOrd   = $nextOrd + 1;
        $this->showMatPlanForm = true;
    }

    public function editarMatPlan(int $id): void
    {
        $mat = MatPlan::findOrFail($id);
        $this->editMatPlanId   = $mat->id;
        $this->matPlanMateria  = $mat->matPlanMateria;
        $this->matPlanOrd      = $mat->ord;
        $this->matPlanAbrev    = $mat->abrev;
        $this->showMatPlanForm = true;
    }

    public function guardarMatPlan(): void
    {
        $this->validateOnly('matPlanMateria');
        $this->validateOnly('matPlanOrd');
        $this->validateOnly('matPlanAbrev');

        $data = [
            'idCurPlan'     => $this->selectedCurPlanId,
            'matPlanMateria'=> $this->matPlanMateria,
            'ord'           => $this->matPlanOrd,
            'abrev'         => $this->matPlanAbrev,
        ];

        if ($this->editMatPlanId) {
            MatPlan::findOrFail($this->editMatPlanId)->update($data);
            session()->flash('success', 'Materia modelo actualizada.');
        } else {
            MatPlan::create($data);
            session()->flash('success', 'Materia modelo creada.');
        }

        $this->cancelarMatPlan();
        unset($this->matplanes);
    }

    public function confirmarBorrarMatPlan(int $id): void
    {
        $this->confirmDeleteMatPlanId = (string) $id;
    }

    public function borrarMatPlan(): void
    {
        if (! $this->confirmDeleteMatPlanId) {
            return;
        }

        $mat = MatPlan::findOrFail((int) $this->confirmDeleteMatPlanId);

        if ($mat->materias()->exists() || $mat->calificaciones()->exists()) {
            session()->flash('error', 'No se puede eliminar: la materia modelo tiene materias o calificaciones del año asociadas.');
            $this->confirmDeleteMatPlanId = '';
            return;
        }

        $mat->delete();
        $this->confirmDeleteMatPlanId = '';
        session()->flash('success', 'Materia modelo eliminada.');
        unset($this->matplanes);
    }

    public function cancelarMatPlan(): void
    {
        $this->reset(['showMatPlanForm', 'editMatPlanId', 'matPlanMateria', 'matPlanOrd', 'matPlanAbrev', 'confirmDeleteMatPlanId']);
        $this->resetErrorBag(['matPlanMateria', 'matPlanOrd', 'matPlanAbrev']);
    }

    public function render(): View
    {
        return view('livewire.core.cur-plan.index')
            ->layout('layouts.staff');
    }
}
