<?php

namespace App\Livewire\Core\Ento;

use App\Models\Core\Ento;
use App\Models\Core\Nivel;
use App\Models\Core\Terlec;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class EntoIndex extends Component
{
    public bool $showForm = false;
    public ?int $editId   = null;

    // Institucional
    #[Rule(['required', 'integer', 'exists:niveles,id'])]
    public ?int $idNivel = null;

    #[Rule(['nullable', 'string', 'max:255'])]
    public string $insti = '';

    #[Rule(['nullable', 'string', 'max:20'])]
    public string $cue = '';

    #[Rule(['nullable', 'string', 'max:14'])]
    public string $cuit = '';

    #[Rule(['nullable', 'string', 'max:100'])]
    public string $direccion = '';

    #[Rule(['nullable', 'string', 'max:100'])]
    public string $localidad = '';

    #[Rule(['nullable', 'string', 'max:50'])]
    public string $telefono = '';

    #[Rule(['nullable', 'email', 'max:100'])]
    public string $mail = '';

    // Autogestión
    #[Rule(['nullable', 'integer', 'exists:terlec,id'])]
    public ?int $idTerlecVerNotas = null;

    // Feature flags
    public bool $platOff         = false;
    public bool $cargaNotasOff   = false;
    public bool $verNotasOff     = false;
    public bool $matriculaWebOff = false;

    #[Rule(['nullable', 'string', 'max:500'])]
    public string $offMensaje = '';

    #[Computed]
    public function entos()
    {
        return Ento::with(['nivel', 'terlecAutogesion'])->get();
    }

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

    public function editar(int $id): void
    {
        $ento = Ento::findOrFail($id);
        $this->editId            = $ento->Id;
        $this->idNivel           = $ento->idNivel;
        $this->insti             = $ento->insti ?? '';
        $this->cue               = $ento->cue ?? '';
        $this->cuit              = $ento->cuit ?? '';
        $this->direccion         = $ento->direccion ?? '';
        $this->localidad         = $ento->localidad ?? '';
        $this->telefono          = $ento->telefono ?? '';
        $this->mail              = $ento->mail ?? '';
        $this->idTerlecVerNotas  = $ento->idTerlecVerNotas;
        $this->platOff           = (bool) $ento->platOff;
        $this->cargaNotasOff     = (bool) $ento->cargaNotasOff;
        $this->verNotasOff       = (bool) $ento->verNotasOff;
        $this->matriculaWebOff   = (bool) $ento->matriculaWebOff;
        $this->offMensaje        = $ento->offMensaje ?? '';
        $this->showForm          = true;
    }

    public function guardar(): void
    {
        $this->validate();

        $data = [
            'idNivel'          => $this->idNivel,
            'insti'            => $this->insti,
            'cue'              => $this->cue,
            'cuit'             => $this->cuit,
            'direccion'        => $this->direccion,
            'localidad'        => $this->localidad,
            'telefono'         => $this->telefono,
            'mail'             => $this->mail,
            'idTerlecVerNotas' => $this->idTerlecVerNotas,
            'platOff'          => $this->platOff ? 1 : 0,
            'cargaNotasOff'    => $this->cargaNotasOff ? 1 : 0,
            'verNotasOff'      => $this->verNotasOff ? 1 : 0,
            'matriculaWebOff'  => $this->matriculaWebOff ? 1 : 0,
            'offMensaje'       => $this->offMensaje,
        ];

        Ento::findOrFail($this->editId)->update($data);

        session()->flash('success', 'Entorno actualizado.');
        $this->cancelar();
        unset($this->entos);
    }

    public function cancelar(): void
    {
        $this->reset([
            'showForm', 'editId', 'idNivel', 'insti', 'cue', 'cuit',
            'direccion', 'localidad', 'telefono', 'mail',
            'idTerlecVerNotas', 'platOff', 'cargaNotasOff',
            'verNotasOff', 'matriculaWebOff', 'offMensaje',
        ]);
        $this->resetValidation();
    }

    public function render(): View
    {
        return view('livewire.core.ento.index')
            ->layout('layouts.staff');
    }
}
