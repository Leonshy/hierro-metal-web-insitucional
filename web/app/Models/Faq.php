<?php

namespace App\Models;

use App\Models\Concerns\HasAuditing;
use App\Models\Concerns\Ordenable;
use App\Services\Html\HtmlSanitizer;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['pregunta', 'respuesta', 'orden', 'activo'])]
class Faq extends Model
{
    use HasAuditing, HasFactory, Ordenable;

    protected $table = 'faqs';

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    /** La respuesta admite HTML del editor: se sanea siempre al guardar (lista blanca de config/sitio.php). */
    protected function respuesta(): Attribute
    {
        return Attribute::make(set: fn (?string $value) => app(HtmlSanitizer::class)->clean($value));
    }
}
