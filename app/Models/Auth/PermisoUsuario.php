<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermisoUsuario extends Model
{
    use HasFactory;

    protected $table = 'permisosusuarios';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['orden', 'tema', 'descripcion'];

    protected $casts = [
        'orden' => 'integer',
    ];
}
