<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/** Límite de palabras de un texto del sitio (presupuestos de `docs/ux-flows/planilla-campos.md`). */
class MaxWords implements ValidationRule
{
    public function __construct(private readonly int $max) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $palabras = count(preg_split('/\s+/u', trim(strip_tags((string) $value)), -1, PREG_SPLIT_NO_EMPTY) ?: []);

        if ($palabras > $this->max) {
            $fail("Usá como máximo {$this->max} palabras (ahora tiene {$palabras}).");
        }
    }
}
