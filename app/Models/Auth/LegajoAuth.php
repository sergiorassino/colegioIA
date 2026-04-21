<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Core\Matricula;
use App\Models\Core\Calificacion;
use App\Models\Core\Ento;
use App\Models\Core\Nivel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegajoAuth extends Authenticatable
{
    protected $table = 'legajos';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $hidden = ['pwrd'];

    /**
     * No se usan masivamente desde aquí, la edición de datos
     * va por el modelo Core\Legajo.
     */
    protected $guarded = [];

    /**
     * Campo de contraseña para Laravel Auth.
     */
    public function getAuthPassword(): string
    {
        return $this->pwrd ?? '';
    }

    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'idnivel', 'id');
    }

    public function ento(): ?Ento
    {
        return Ento::where('idNivel', $this->idnivel)->first();
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'idLegajos', 'id');
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class, 'idLegajos', 'id');
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim($this->apellido . ', ' . $this->nombre);
    }
}
