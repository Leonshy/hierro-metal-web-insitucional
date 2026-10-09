<?php

namespace App\Models;

use App\Models\Concerns\HasAuditing;
use App\Models\Concerns\Ordenable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * Una de las secciones de la portada que van debajo del hero y los diferenciales. La `clave` decide qué contenido
 * se muestra (resources/views/home/secciones/{clave}.blade.php); `activo` y `orden` los maneja el cliente.
 */
#[Fillable(['clave', 'nombre', 'activo', 'orden'])]
class SeccionInicio extends Model
{
    use HasAuditing, Ordenable;

    protected $table = 'secciones_inicio';

    /** Claves que la portada sabe dibujar. */
    public const CLAVES = ['productos', 'servicios', 'calidad', 'novedades', 'contacto'];

    protected function casts(): array
    {
        return ['activo' => 'boolean'];
    }
}
