<?php

namespace App\Models;

use App\Models\Concerns\HasAuditing;
use App\Models\Concerns\Ordenable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

#[Fillable(['etiqueta', 'dias', 'abre', 'cierra', 'cerrado', 'orden', 'activo'])]
class Horario extends Model
{
    use HasAuditing, HasFactory, Ordenable;

    protected $table = 'horarios';

    protected function casts(): array
    {
        return [
            'dias' => 'array',
            'cerrado' => 'boolean',
            'activo' => 'boolean',
        ];
    }

    /** Nombres de día en la frase de próxima apertura. */
    public const DIAS = [1 => 'lunes', 2 => 'martes', 3 => 'miércoles', 4 => 'jueves', 5 => 'viernes', 6 => 'sábado', 7 => 'domingo'];

    /**
     * Estado «Abierto ahora / Cerrado» con la hora de Asunción (docs/07 §3.4).
     * No calcula feriados: el texto fijo «Domingos y feriados: cerrado» sigue visible.
     *
     * @param  Collection<int, Horario>|null  $horarios  por defecto, los activos en orden
     * @return array{abierto: bool, texto: string}
     */
    public static function estadoAhora(?CarbonInterface $ahora = null, ?Collection $horarios = null): array
    {
        $ahora = ($ahora ?? now())->copy()->setTimezone('America/Asuncion');
        $horarios ??= static::query()->activos()->ordenados()->get();
        $conHora = $horarios->reject(fn (self $h): bool => $h->cerrado || ! $h->abre || ! $h->cierra);
        $hora = $ahora->format('H:i');

        foreach ($conHora as $h) {
            if (in_array($ahora->dayOfWeekIso, $h->dias, true) && $hora >= substr($h->abre, 0, 5) && $hora < substr($h->cierra, 0, 5)) {
                return ['abierto' => true, 'texto' => 'Abierto ahora · cerramos a las '.substr($h->cierra, 0, 5)];
            }
        }

        for ($d = 0; $d <= 7; $d++) {
            $dia = $ahora->copy()->addDays($d);

            foreach ($conHora->sortBy('abre') as $h) {
                $abre = substr($h->abre, 0, 5);

                if (! in_array($dia->dayOfWeekIso, $h->dias, true) || ($d === 0 && $hora >= $abre)) {
                    continue;
                }

                $cuando = match (true) {
                    $d === 0 => 'hoy',
                    $d === 1 => 'mañana',
                    default => 'el '.self::DIAS[$dia->dayOfWeekIso],
                };

                return ['abierto' => false, 'texto' => "Cerrado ahora · abrimos {$cuando} a las {$abre}"];
            }
        }

        return ['abierto' => false, 'texto' => 'Cerrado ahora'];
    }
}
