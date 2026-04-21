<?php

namespace App\Auth;

class ContextoStaff
{
    private ?int $nivelId  = null;
    private ?int $terlecId = null;

    public function cargarDesdeSession(): void
    {
        $this->nivelId  = session('nivel_id');
        $this->terlecId = session('terlec_id');
    }

    public function nivelId(): ?int
    {
        return $this->nivelId;
    }

    public function terlecId(): ?int
    {
        return $this->terlecId;
    }

    public function estaCompleto(): bool
    {
        return $this->nivelId !== null && $this->terlecId !== null;
    }
}
