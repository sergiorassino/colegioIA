<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos';

    /**
     * La PK en la BD es 'Id' con mayúscula.
     */
    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'orden', 'idCurPlan', 'idTerlec', 'idNivel', 'cursec', 'c', 's', 'turno',
    ];

    protected $casts = [
        'orden' => 'integer',
    ];

    public function curPlan(): BelongsTo
    {
        return $this->belongsTo(CurPlan::class, 'idCurPlan', 'id');
    }

    public function terlec(): BelongsTo
    {
        return $this->belongsTo(Terlec::class, 'idTerlec', 'id');
    }

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'idNivel', 'id');
    }

    /**
     * La FK en materias referencia cursos.Id (mayúscula).
     */
    public function materias(): HasMany
    {
        return $this->hasMany(Materia::class, 'idCursos', 'Id');
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'idCursos', 'Id');
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class, 'idCursos', 'Id');
    }
}
