<?php

namespace App\Livewire\Core\Legajos;

use App\Models\Core\Legajo;
use App\Models\Core\Nivel;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class LegajoIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public ?int $filtroNivel = null;

    #[Computed]
    public function niveles()
    {
        return Nivel::orderBy('nivel')->get();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFiltroNivel(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $legajos = Legajo::with('nivel')
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('apellido', 'like', "%{$this->search}%")
                       ->orWhere('nombre', 'like', "%{$this->search}%")
                       ->orWhere('dni', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filtroNivel, fn ($q) => $q->where('idnivel', $this->filtroNivel))
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->paginate(25);

        return view('livewire.core.legajos.index', compact('legajos'))
            ->layout('layouts.staff');
    }
}
