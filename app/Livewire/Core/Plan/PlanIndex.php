<?php

namespace App\Livewire\Core\Plan;

use App\Models\Core\Nivel;
use App\Models\Core\Plan;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class PlanIndex extends Component
{
    public bool   $showForm = false;
    public ?int   $editId   = null;

    #[Rule(['required', 'integer', 'exists:niveles,id'])]
    public ?int $idNivel = null;

    #[Rule(['required', 'string', 'max:70'])]
    public string $plan = '';

    #[Rule(['nullable', 'string', 'max:5'])]
    public ?string $abrev = null;

    public string $confirmDeleteId = '';

    #[Computed]
    public function planes()
    {
        return Plan::with('nivel')->orderBy('idNivel')->orderBy('plan')->get();
    }

    #[Computed]
    public function niveles()
    {
        return Nivel::orderBy('nivel')->get();
    }

    public function crear(): void
    {
        $this->reset(['editId', 'idNivel', 'plan', 'abrev']);
        $this->showForm = true;
    }

    public function editar(int $id): void
    {
        $plan = Plan::findOrFail($id);
        $this->editId   = $plan->id;
        $this->idNivel  = $plan->idNivel;
        $this->plan     = $plan->plan;
        $this->abrev    = $plan->abrev;
        $this->showForm = true;
    }

    public function guardar(): void
    {
        $this->validate();

        if ($this->editId) {
            Plan::findOrFail($this->editId)->update([
                'idNivel' => $this->idNivel,
                'plan'    => $this->plan,
                'abrev'   => $this->abrev,
            ]);
            session()->flash('success', 'Plan de estudio actualizado.');
        } else {
            Plan::create([
                'idNivel' => $this->idNivel,
                'plan'    => $this->plan,
                'abrev'   => $this->abrev,
            ]);
            session()->flash('success', 'Plan de estudio creado.');
        }

        $this->cancelar();
        unset($this->planes);
    }

    public function confirmarBorrar(int $id): void
    {
        $this->confirmDeleteId = (string) $id;
    }

    public function borrar(): void
    {
        if (! $this->confirmDeleteId) {
            return;
        }

        $plan = Plan::findOrFail((int) $this->confirmDeleteId);

        if ($plan->curplanes()->exists()) {
            session()->flash('error', 'No se puede eliminar: el plan tiene cursos modelo asociados.');
            $this->confirmDeleteId = '';
            return;
        }

        $plan->delete();
        $this->confirmDeleteId = '';
        session()->flash('success', 'Plan de estudio eliminado.');
        unset($this->planes);
    }

    public function cancelar(): void
    {
        $this->reset(['showForm', 'editId', 'idNivel', 'plan', 'abrev', 'confirmDeleteId']);
        $this->resetValidation();
    }

    public function render(): View
    {
        return view('livewire.core.plan.index')
            ->layout('layouts.staff');
    }
}
