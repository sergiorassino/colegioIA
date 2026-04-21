<?php

namespace App\Auth;

use App\Models\Auth\LegajoAuth;
use App\Models\Core\Ento;
use App\Models\Core\Terlec;
use Illuminate\Support\Facades\Auth;

class ContextoAutogestion
{
    private ?LegajoAuth $alumno    = null;
    private ?Ento       $ento      = null;
    private ?Terlec     $terlec    = null;

    public function cargar(): void
    {
        /** @var LegajoAuth|null $alumno */
        $alumno = Auth::guard('alumno')->user();

        if ($alumno === null) {
            return;
        }

        $this->alumno = $alumno;
        $this->ento   = Ento::where('idNivel', $alumno->idnivel)->first();
        $this->terlec = $this->ento
            ? Terlec::find($this->ento->idTerlecVerNotas)
            : null;
    }

    public function alumno(): ?LegajoAuth
    {
        return $this->alumno;
    }

    public function ento(): ?Ento
    {
        return $this->ento;
    }

    public function terlecAutogesion(): ?Terlec
    {
        return $this->terlec;
    }

    public function nivelId(): ?int
    {
        return $this->alumno?->idnivel;
    }

    public function terlecId(): ?int
    {
        return $this->ento?->idTerlecVerNotas;
    }

    public function platOff(): bool
    {
        return (bool) ($this->ento?->platOff ?? false);
    }

    public function verNotasOff(): bool
    {
        return (bool) ($this->ento?->verNotasOff ?? false);
    }

    public function cargaNotasOff(): bool
    {
        return (bool) ($this->ento?->cargaNotasOff ?? false);
    }

    public function matriculaWebOff(): bool
    {
        return (bool) ($this->ento?->matriculaWebOff ?? false);
    }
}
