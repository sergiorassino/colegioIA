<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matricula';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'idTerlec', 'idNivel', 'idCursos', 'idLegajos', 'idCondiciones',
        'obsMatr', 'idCuotasbecas', 'nroMatricula', 'fechaMatricula', 'obsAnual',
        'conducta1', 'conducta2', 'acept1', 'acept2', 'acept3', 'acept4', 'inscripto',
    ];

    protected $casts = [
        'fechaMatricula' => 'date',
        'acept1'         => 'integer',
        'acept2'         => 'integer',
        'acept3'         => 'integer',
        'acept4'         => 'integer',
        'inscripto'      => 'integer',
    ];

    public function terlec(): BelongsTo
    {
        return $this->belongsTo(Terlec::class, 'idTerlec', 'id');
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

    public function legajo(): BelongsTo
    {
        return $this->belongsTo(Legajo::class, 'idLegajos', 'id');
    }

    public function condicion(): BelongsTo
    {
        return $this->belongsTo(Condicion::class, 'idCondiciones', 'id');
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class, 'idMatricula', 'id');
    }
}
