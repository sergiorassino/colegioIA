<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CurPlan extends Model
{
    use HasFactory;

    protected $table = 'curplan';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['idPlan', 'curPlanCurso'];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'idPlan', 'id');
    }

    public function matplanes(): HasMany
    {
        return $this->hasMany(MatPlan::class, 'idCurPlan', 'id');
    }

    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class, 'idCurPlan', 'id');
    }

    public function materias(): HasMany
    {
        return $this->hasMany(Materia::class, 'idCurPlan', 'id');
    }
}
