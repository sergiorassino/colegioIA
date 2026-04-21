<?php

namespace App\Livewire\Core\Matriculas;

use App\Models\Core\Condicion;
use App\Models\Core\Curso;
use App\Models\Core\Legajo;
use App\Models\Core\Matricula;
use App\Models\Core\Nivel;
use App\Models\Core\Terlec;
use App\Services\MatricularAlumnoService;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MatriculaIndex extends Component
{
    use WithPagination;

    // ── Filtros lista ─────────────────────────────────────────
    #[Url]
    public ?int $filtroTerlec = null;
    #[Url]
    public ?int $filtroNivel  = null;
    #[Url]
    public string $search = '';

    // ── Formulario nueva matrícula ────────────────────────────
    public bool $showForm = false;
    public string $searchAlumno = '';

    #[Rule(['required', 'integer', 'exists:legajos,id'])]
    public ?int $idLegajos = null;

    public ?string $alumnoNombre = null;

    #[Rule(['required', 'integer', 'exists:terlec,id'])]
    public ?int $idTerlec = null;

    #[Rule(['required', 'integer', 'exists:niveles,id'])]
    public ?int $idNivel = null;

    #[Rule(['required', 'integer', 'exists:cursos,Id'])]
    public ?int $idCursos = null;

    #[Rule(['required', 'integer', 'exists:condiciones,id'])]
    public ?int $idCondiciones = null;

    #[Rule(['nullable', 'date'])]
    public ?string $fechaMatricula = null;

    public string $confirmDeleteId = '';

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
    public function condiciones()
    {
        return Condicion::where('proteg', 0)->orderBy('orden')->get();
    }

    #[Computed]
    public function cursosPorNivel()
    {
        if (! $this->idNivel || ! $this->idTerlec) {
            return collect();
        }

        return Curso::where('idNivel', $this->idNivel)
            ->where('idTerlec', $this->idTerlec)
            ->orderBy('cursec')
            ->get();
    }

    #[Computed]
    public function alumnosBusqueda()
    {
        if (strlen($this->searchAlumno) < 2) {
            return collect();
        }

        return Legajo::where(function ($q) {
            $q->where('apellido', 'like', "%{$this->searchAlumno}%")
              ->orWhere('nombre', 'like', "%{$this->searchAlumno}%")
              ->orWhere('dni', 'like', "%{$this->searchAlumno}%");
        })
        ->orderBy('apellido')
        ->limit(10)
        ->get();
    }

    public function mount(): void
    {
        $this->filtroTerlec = session('terlec_id');
        $this->filtroNivel  = session('nivel_id');
        $this->idTerlec     = session('terlec_id');
        $this->idNivel      = session('nivel_id');
        $this->fechaMatricula = now()->toDateString();
    }

    public function seleccionarAlumno(int $id, string $nombre): void
    {
        $this->idLegajos   = $id;
        $this->alumnoNombre = $nombre;
        $this->searchAlumno = '';
        unset($this->alumnosBusqueda);
    }

    public function matricular(MatricularAlumnoService $servicio): void
    {
        $this->validate();

        try {
            $servicio->matricular([
                'idLegajos'     => $this->idLegajos,
                'idTerlec'      => $this->idTerlec,
                'idNivel'       => $this->idNivel,
                'idCursos'      => $this->idCursos,
                'idCondiciones' => $this->idCondiciones,
                'fechaMatricula'=> $this->fechaMatricula,
            ]);

            session()->flash('success', "Alumno/a matriculado correctamente en el curso seleccionado.");
            $this->cancelar();
        } catch (\RuntimeException $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function iniciarFormulario(): void
    {
        $this->reset(['idLegajos', 'alumnoNombre', 'searchAlumno', 'idCursos', 'idCondiciones', 'confirmDeleteId']);
        $this->idTerlec = session('terlec_id') ?? $this->idTerlec;
        $this->idNivel  = session('nivel_id')  ?? $this->idNivel;
        $this->fechaMatricula = now()->toDateString();
        $this->showForm = true;
    }

    public function confirmarBorrar(int $id): void
    {
        $this->confirmDeleteId = (string) $id;
    }

    public function borrar(MatricularAlumnoService $servicio): void
    {
        if (! $this->confirmDeleteId) {
            return;
        }

        try {
            $servicio->cancelar((int) $this->confirmDeleteId);
            session()->flash('success', 'Matrícula cancelada correctamente.');
        } catch (\RuntimeException $e) {
            session()->flash('error', $e->getMessage());
        }

        $this->confirmDeleteId = '';
        $this->resetPage();
    }

    public function cancelar(): void
    {
        $this->reset(['showForm', 'idLegajos', 'alumnoNombre', 'searchAlumno', 'idCursos', 'idCondiciones', 'confirmDeleteId']);
        $this->resetValidation();
    }

    public function render(): View
    {
        $matriculas = Matricula::with(['legajo', 'curso', 'terlec', 'nivel', 'condicion'])
            ->when($this->filtroTerlec, fn ($q) => $q->where('idTerlec', $this->filtroTerlec))
            ->when($this->filtroNivel,  fn ($q) => $q->where('idNivel',  $this->filtroNivel))
            ->when($this->search, function ($q) {
                $q->whereHas('legajo', function ($q2) {
                    $q2->where('apellido', 'like', "%{$this->search}%")
                       ->orWhere('nombre', 'like', "%{$this->search}%")
                       ->orWhere('dni', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('idNivel')
            ->paginate(25);

        return view('livewire.core.matriculas.index', compact('matriculas'))
            ->layout('layouts.staff');
    }
}
