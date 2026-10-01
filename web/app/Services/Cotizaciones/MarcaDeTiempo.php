<?php

namespace App\Services\Cotizaciones;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

/**
 * Marca de tiempo firmada del formulario. Va en un campo oculto y se cifra con la clave de la app:
 * un robot que no la trae, la falsifica o envía el formulario en menos de unos segundos queda marcado como spam.
 */
class MarcaDeTiempo
{
    public static function emitir(): string
    {
        return Crypt::encryptString((string) now()->timestamp);
    }

    /** Segundos desde que se mostró el formulario, o null si la marca falta o no es válida. */
    public static function edadEnSegundos(?string $marca): ?int
    {
        if (blank($marca)) {
            return null;
        }

        try {
            return max(0, now()->timestamp - (int) Crypt::decryptString($marca));
        } catch (DecryptException) {
            return null;
        }
    }
}
