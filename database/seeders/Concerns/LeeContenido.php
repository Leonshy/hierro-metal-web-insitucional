<?php

namespace Database\Seeders\Concerns;

/**
 * Lee `database/seeders/data/contenido.json`: el contenido del cliente (docs/01), las medidas del catálogo 2026
 * (docs/05) y el copy final (docs/07), generado a partir de esos documentos.
 *
 * Los seeders **crean lo que falta y nunca pisan lo que ya existe**: volver a correrlos en producción no deshace
 * lo que el cliente editó desde el panel.
 */
trait LeeContenido
{
    /** @return array<mixed> */
    protected function contenido(string $clave): array
    {
        static $datos = null;

        $datos ??= json_decode((string) file_get_contents(database_path('seeders/data/contenido.json')), true, flags: JSON_THROW_ON_ERROR);

        return $datos[$clave];
    }
}
