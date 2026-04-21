<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class AlumnoLoginForm extends Component
{
    public string $dni      = '';
    public string $password = '';

    public function login(): void
    {
        $this->validate([
            'dni'      => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('alumno')->attempt([
            'dni'      => $this->dni,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'dni' => __('Las credenciales proporcionadas son incorrectas.'),
            ]);
        }

        session()->regenerate();

        $this->redirect(route('alumno.dashboard'), navigate: true);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.auth.alumno-login-form')
            ->layout('layouts.auth');
    }
}
