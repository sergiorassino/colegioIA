<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Nivel extends Model
{
    use HasFactory;

    protected $table = 'niveles';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['nivel', 'abrev'];

    public function planes(): HasMany
    {
        return $this->hasMany(Plan::class, 'idNivel', 'id');
    }

    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class, 'idNivel', 'id');
    }

    public function materias(): HasMany
    {
        return $this->hasMany(Materia::class, 'idNivel', 'id');
    }

    public function legajos(): HasMany
    {
        return $this->hasMany(Legajo::class, 'idnivel', 'id');
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'idNivel', 'id');
    }

    public function ento(): HasOne
    {
        return $this->hasOne(Ento::class, 'idNivel', 'id');
    }
}
