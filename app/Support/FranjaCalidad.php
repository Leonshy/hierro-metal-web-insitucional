<?php

namespace App\Support;

/**
 * La franja amarilla de calidad que aparece en la portada y en Servicios. Sus textos se editan en la sección
 * Calidad del panel; si un campo está vacío se usa el texto de siempre (los de abajo, única fuente de verdad).
 */
final class FranjaCalidad
{
    public const DONDE = ['inicio', 'servicios'];

    public const POR_DEFECTO = [
        'inicio' => [
            'titulo' => 'Materia prima certificada bajo Normas Internacionales del Acero',
            'bajada' => 'Control de calidad estricto, infraestructura mantenida y mejora continua de productos y procesos.',
            'boton' => 'Leer la política completa',
        ],
        'servicios' => [
            'titulo' => 'El trabajo de taller se controla igual que el material',
            'bajada' => 'Materia prima certificada bajo Normas Internacionales del Acero, control de medidas antes de despachar e infraestructura mantenida.',
            'boton' => 'Leer la política de calidad',
        ],
    ];

    /** @return array{titulo: string, bajada: string, boton: string} */
    public static function de(string $donde): array
    {
        $encabezado = Encabezado::de('calidad');

        return collect(self::POR_DEFECTO[$donde])
            ->map(fn (string $porDefecto, string $campo): string => (string) $encabezado->dato("franja_{$donde}_{$campo}", $porDefecto))
            ->all();
    }

    /** Las claves que se guardan en el bloque hero de la página de calidad. @return array<int, string> */
    public static function claves(): array
    {
        return collect(self::DONDE)->flatMap(fn (string $donde): array => collect(['titulo', 'bajada', 'boton'])->map(fn (string $campo): string => "franja_{$donde}_{$campo}")->all())->all();
    }
}
