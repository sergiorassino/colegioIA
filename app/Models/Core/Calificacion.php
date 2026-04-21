<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = 'calificaciones';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'idLegajos', 'idMatricula', 'ord', 'idTerlec', 'idCursos', 'idMaterias', 'idMatPlan',
        'ic01', 'ic02', 'ic03', 'ic04', 'ic05', 'ic06', 'ic07', 'ic08', 'ic09', 'ic10',
        'ic11', 'ic12', 'ic13', 'ic14', 'ic15', 'ic16', 'ic17', 'ic18', 'ic19', 'ic20',
        'ic21', 'ic22', 'ic23', 'ic24', 'ic25', 'ic26', 'ic27', 'ic28', 'ic29', 'ic30',
        'ic31', 'ic32', 'ic33', 'ic34', 'ic35', 'ic36', 'ic37', 'ic38', 'ic39', 'ic40',
        'obs01', 'obs02',
        'tm1', 'tm2', 'tm3', 'tm4', 'tm5', 'tm6', 'tmNota',
        'dic', 'feb',
        'inscri', 'condAdeuda', 'apro', 'calif', 'mes', 'ano', 'cond', 'escuapro',
        'libro', 'folio', 'fechApro',
        'libroDic', 'folioDic', 'fechAproDic',
        'libroFeb', 'folioFeb', 'fechAproFeb',
    ];

    protected $casts = [
        'fechApro'    => 'date',
        'fechAproDic' => 'date',
        'fechAproFeb' => 'date',
        'inscri'      => 'integer',
        'apro'        => 'integer',
        'ord'         => 'integer',
    ];

    public function legajo(): BelongsTo
    {
        return $this->belongsTo(Legajo::class, 'idLegajos', 'id');
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class, 'idMatricula', 'id');
    }

    public function terlec(): BelongsTo
    {
        return $this->belongsTo(Terlec::class, 'idTerlec', 'id');
    }

    /**
     * FK hacia cursos.Id (mayúscula).
     */
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'idCursos', 'Id');
    }

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class, 'idMaterias', 'id');
    }

    public function matPlan(): BelongsTo
    {
        return $this->belongsTo(MatPlan::class, 'idMatPlan', 'id');
    }
}
