<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materia extends Model
{
    use HasFactory;

    protected $table = 'materias';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'ord', 'idCurPlan', 'idMatPlan', 'idNivel', 'idCursos', 'idTerlec',
        'materia', 'abrev', 'cierre1e', 'cierre2e',
    ];

    protected $casts = [
        'ord'      => 'integer',
        'idMatPlan' => 'integer',
        'cierre1e' => 'integer',
        'cierre2e' => 'integer',
    ];

    public function curPlan(): BelongsTo
    {
        return $this->belongsTo(CurPlan::class, 'idCurPlan', 'id');
    }

    /**
     * Vínculo CRÍTICO para previas y analítico.
     * NUNCA modificar idMatPlan sin pasar por MateriaIntegridadService.
     */
    public function matPlan(): BelongsTo
    {
        return $this->belongsTo(MatPlan::class, 'idMatPlan', 'id');
    }

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'idNivel', 'id');
    }

    /**
     * FK hacia cursos.Id (mayúscula).
     */
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'idCursos', 'Id');
    }

    public function terlec(): BelongsTo
    {
        return $this->belongsTo(Terlec::class, 'idTerlec', 'id');
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class, 'idMaterias', 'id');
    }

    public function esExtraordinaria(): bool
    {
        return $this->idMatPlan === 0 || $this->idMatPlan === null;
    }
}
