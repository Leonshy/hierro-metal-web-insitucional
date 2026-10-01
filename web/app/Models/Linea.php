<?php

namespace App\Models;

use App\Models\Concerns\HasAuditing;
use App\Models\Concerns\Ordenable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Línea de producto dentro de una familia (por ejemplo «Chapas galvanizadas»).
 *
 * `medidas` guarda una lista de tablas tal como se editan en el panel:
 * [{titulo, modo: tabla|lista, columnas: string[], filas_texto: string}].
 * `tablas()` las convierte a filas listas para mostrar.
 *
 * @property array<int, array<string, mixed>>|null $medidas
 */
#[Fillable(['familia_id', 'nombre', 'descripcion', 'usos', 'medidas', 'nota_medidas', 'orden', 'activo'])]
class Linea extends Model
{
    use HasAuditing, HasFactory, Ordenable;

    protected $table = 'lineas';

    protected function casts(): array
    {
        return [
            'usos' => 'array',
            'medidas' => 'array',
            'activo' => 'boolean',
        ];
    }

    public function familia(): BelongsTo
    {
        return $this->belongsTo(Familia::class);
    }

    /**
     * Pasa el texto del editor a filas. Una fila por renglón; las celdas se separan con
     * tabulación (lo que genera Excel al copiar) o con punto y coma.
     *
     * @return array<int, array<int, string>>
     */
    public static function parseFilas(?string $texto): array
    {
        $filas = [];

        foreach (preg_split('/\R/u', (string) $texto) ?: [] as $renglon) {
            if (trim($renglon) === '') {
                continue;
            }

            $separador = str_contains($renglon, "\t") ? "\t" : ';';
            $filas[] = array_map(fn (string $celda): string => trim($celda), explode($separador, $renglon));
        }

        return $filas;
    }

    /**
     * @return array<int, array{titulo: ?string, modo: string, columnas: array<int, string>, filas: array<int, array<int, string>>}>
     */
    public function tablas(): array
    {
        return collect($this->medidas ?? [])
            ->map(fn (array $tabla): array => [
                'titulo' => $tabla['titulo'] ?? null,
                'modo' => $tabla['modo'] ?? 'tabla',
                'columnas' => array_values($tabla['columnas'] ?? []),
                'filas' => self::parseFilas($tabla['filas_texto'] ?? ''),
            ])
            ->all();
    }

    public function tieneMedidas(): bool
    {
        return count($this->tablas()) > 0;
    }
}
