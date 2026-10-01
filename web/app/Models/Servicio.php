<?php

namespace App\Models;

use App\Models\Concerns\HasAuditing;
use App\Models\Concerns\Ordenable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nombre', 'descripcion', 'usos', 'destacado_home', 'titulo_home', 'resumen_home', 'orden', 'activo'])]
class Servicio extends Model
{
    use HasAuditing, HasFactory, Ordenable;

    protected $table = 'servicios';

    protected function casts(): array
    {
        return [
            'usos' => 'array',
            'destacado_home' => 'boolean',
            'activo' => 'boolean',
        ];
    }
}
