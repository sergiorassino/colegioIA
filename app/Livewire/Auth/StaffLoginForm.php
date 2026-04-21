<?php

namespace App\Livewire\Auth;

use App\Models\Core\Nivel;
use App\Models\Core\Terlec;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class StaffLoginForm extends Component
{
    public string $dni      = '';
    public string $password = '';
    public ?int   $nivel_id  = null;
    public ?int   $terlec_id = null;

    /** @var array<int, array{id: int, nivel: string}> */
    public array $niveles  = [];
    /** @var array<int, array{id: int, ano: string|int}> */
    public array $terlecs  = [];

    public function mount(): void
    {
        $this->niveles = Nivel::orderBy('nivel')->get(['id', 'nivel'])->toArray();
        $this->terlecs = Terlec::orderBy('orden')->get(['id', 'ano'])->toArray();
    }

    public function login(): void
    {
        $this->validate([
            'dni'      => ['required', 'string'],
            'password' => ['required', 'string'],
            'nivel_id'  => ['required', 'integer', 'exists:niveles,id'],
            'terlec_id' => ['required', 'integer', 'exists:terlec,id'],
        ], [
            'nivel_id.required'  => 'Debe seleccionar un nivel.',
            'terlec_id.required' => 'Debe seleccionar un ciclo lectivo.',
        ]);

        if (! Auth::guard('staff')->attempt([
            'dni'      => $this->dni,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'dni' => __('Las credenciales proporcionadas son incorrectas.'),
            ]);
        }

        session([
            'nivel_id'  => $this->nivel_id,
            'terlec_id' => $this->terlec_id,
        ]);

        session()->regenerate();

        $this->redirect(route('staff.dashboard'), navigate: true);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.auth.staff-login-form')
            ->layout('layouts.auth');
    }
}
