<?php

namespace App\Support;

/**
 * Parte un texto largo en bloques independientes, uno por cada título de sección (<h2>). Lo que está antes del
 * primer título queda como introducción. Se usa para que, por ejemplo, la política de calidad se edite en
 * textos separados (introducción, «Qué significa esto para tu obra», «Ámbito de aplicación») y no en uno solo.
 */
final class TextoEnBloques
{
    /** @return array<int, string> */
    public static function partir(string $html): array
    {
        $partes = preg_split('/(?=<h2\b)/i', $html, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return array_values(array_filter(array_map('trim', $partes), fn (string $parte): bool => trim(strip_tags($parte)) !== ''));
    }
}
