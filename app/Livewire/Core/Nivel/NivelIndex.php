<?php

namespace App\Livewire\Core\Nivel;

use App\Models\Core\Nivel;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class NivelIndex extends Component
{
    public bool   $showForm = false;
    public ?int   $editId   = null;

    #[Rule(['required', 'string', 'max:60'])]
    public string $nivel = '';

    #[Rule(['nullable', 'string', 'max:5'])]
    public ?string $abrev = null;

    public string $confirmDeleteId = '';

    #[Computed]
    public function niveles()
    {
        return Nivel::orderBy('nivel')->get();
    }

    public function crear(): void
    {
        $this->reset(['editId', 'nivel', 'abrev']);
        $this->showForm = true;
    }

    public function editar(int $id): void
    {
        $nivel = Nivel::findOrFail($id);
        $this->editId   = $nivel->id;
        $this->nivel    = $nivel->nivel;
        $this->abrev    = $nivel->abrev;
        $this->showForm = true;
    }

    public function guardar(): void
    {
        $this->validate();

        if ($this->editId) {
            Nivel::findOrFail($this->editId)->update([
                'nivel' => $this->nivel,
                'abrev' => $this->abrev,
            ]);
            session()->flash('success', 'Nivel actualizado.');
        } else {
            Nivel::create([
                'nivel' => $this->nivel,
                'abrev' => $this->abrev,
            ]);
            session()->flash('success', 'Nivel creado.');
        }

        $this->cancelar();
        unset($this->niveles);
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

        $nivel = Nivel::findOrFail((int) $this->confirmDeleteId);

        if ($nivel->planes()->exists() || $nivel->cursos()->exists() || $nivel->legajos()->exists()) {
            session()->flash('error', 'No se puede eliminar: el nivel tiene registros asociados.');
            $this->confirmDeleteId = '';
            return;
        }

        $nivel->delete();
        $this->confirmDeleteId = '';
        session()->flash('success', 'Nivel eliminado.');
        unset($this->niveles);
    }

    public function cancelar(): void
    {
        $this->reset(['showForm', 'editId', 'nivel', 'abrev', 'confirmDeleteId']);
        $this->resetValidation();
    }

    public function render(): View
    {
        return view('livewire.core.nivel.index')
            ->layout('layouts.staff');
    }
}
