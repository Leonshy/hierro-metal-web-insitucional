<?php

namespace App\Models;

use App\Models\Concerns\HasAuditing;
use App\Models\Concerns\Ordenable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['slug', 'nombre', 'familia_id', 'servicio_id', 'orden', 'activo'])]
class Rubro extends Model
{
    use HasAuditing, HasFactory, Ordenable;

    protected $table = 'rubros';

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function familia(): BelongsTo
    {
        return $this->belongsTo(Familia::class);
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class);
    }
}
