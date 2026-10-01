<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/** Límite de elementos de una lista (por ejemplo, las etiquetas de uso). */
class MaxItems implements ValidationRule
{
    public function __construct(private readonly int $max) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_array($value) && count($value) > $this->max) {
            $fail("Cargá como máximo {$this->max} elementos.");
        }
    }
}
