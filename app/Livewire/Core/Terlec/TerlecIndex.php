<?php

namespace App\Livewire\Core\Terlec;

use App\Models\Core\Terlec;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class TerlecIndex extends Component
{
    public bool   $showForm = false;
    public ?int   $editId   = null;

    #[Rule(['required', 'integer', 'min:1900', 'max:2100'])]
    public ?int $ano = null;

    #[Rule(['required', 'integer', 'min:0'])]
    public ?int $orden = null;

    public string $confirmDeleteId = '';

    #[Computed]
    public function terlecs()
    {
        return Terlec::orderBy('orden')->get();
    }

    public function crear(): void
    {
        $this->reset(['editId', 'ano', 'orden']);
        $this->orden   = (int) (Terlec::max('orden') ?? 0) + 1;
        $this->showForm = true;
    }

    public function editar(int $id): void
    {
        $terlec = Terlec::findOrFail($id);
        $this->editId  = $terlec->id;
        $this->ano     = $terlec->ano;
        $this->orden   = $terlec->orden;
        $this->showForm = true;
    }

    public function guardar(): void
    {
        $this->validate();

        if ($this->editId) {
            Terlec::findOrFail($this->editId)->update([
                'ano'   => $this->ano,
                'orden' => $this->orden,
            ]);
            session()->flash('success', 'Ciclo lectivo actualizado.');
        } else {
            Terlec::create([
                'ano'   => $this->ano,
                'orden' => $this->orden,
            ]);
            session()->flash('success', 'Ciclo lectivo creado.');
        }

        $this->cancelar();
        unset($this->terlecs);
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

        $terlec = Terlec::findOrFail((int) $this->confirmDeleteId);

        if ($terlec->cursos()->exists() || $terlec->materias()->exists() || $terlec->matriculas()->exists()) {
            session()->flash('error', 'No se puede eliminar: el ciclo lectivo tiene registros asociados.');
            $this->confirmDeleteId = '';
            return;
        }

        $terlec->delete();
        $this->confirmDeleteId = '';
        session()->flash('success', 'Ciclo lectivo eliminado.');
        unset($this->terlecs);
    }

    public function cancelar(): void
    {
        $this->reset(['showForm', 'editId', 'ano', 'orden', 'confirmDeleteId']);
        $this->resetValidation();
    }

    public function render(): View
    {
        return view('livewire.core.terlec.index')
            ->layout('layouts.staff');
    }
}
