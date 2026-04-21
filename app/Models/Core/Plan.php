<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'planes';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['idNivel', 'plan', 'abrev'];

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'idNivel', 'id');
    }

    public function curplanes(): HasMany
    {
        return $this->hasMany(CurPlan::class, 'idPlan', 'id');
    }
}
