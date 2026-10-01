<?php

namespace App\Models;

use App\Models\Concerns\HasAuditing;
use App\Models\Concerns\Ordenable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nombre', 'telefono', 'orden', 'activo'])]
class Vendedor extends Model
{
    use HasAuditing, HasFactory, Ordenable;

    protected $table = 'vendedores';

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }
}
