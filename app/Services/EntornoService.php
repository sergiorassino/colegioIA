<?php

namespace App\Services;

use App\Models\Core\Ento;
use App\Models\Core\Terlec;
use Illuminate\Database\Eloquent\Collection;

class EntornoService
{
    /**
     * Devuelve el entorno para un nivel específico.
     */
    public function porNivel(int $nivelId): ?Ento
    {
        return Ento::where('idNivel', $nivelId)->first();
    }

    /**
     * Devuelve todos los entornos activos.
     *
     * @return Collection<int, Ento>
     */
    public function todos(): Collection
    {
        return Ento::with('nivel')->get();
    }

    /**
     * Devuelve el ciclo lectivo de autogestión para un nivel.
     */
    public function terlecAutogestion(int $nivelId): ?Terlec
    {
        $ento = $this->porNivel($nivelId);

        if ($ento === null || $ento->idTerlecVerNotas === null) {
            return null;
        }

        return Terlec::find($ento->idTerlecVerNotas);
    }

    /**
     * Indica si la plataforma de autogestión está deshabilitada para un nivel.
     */
    public function plataformaOff(int $nivelId): bool
    {
        return (bool) ($this->porNivel($nivelId)?->platOff ?? true);
    }

    /**
     * Indica si la visualización de notas está deshabilitada.
     */
    public function verNotasOff(int $nivelId): bool
    {
        return (bool) ($this->porNivel($nivelId)?->verNotasOff ?? true);
    }
}
