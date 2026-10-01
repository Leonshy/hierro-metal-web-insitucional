<?php

namespace App\Services\Cotizaciones;

use App\Models\SiteSetting;

/**
 * A quién se le avisa de una cotización nueva. Se carga en el panel
 * (ajuste `email_notificacion_cotizaciones`, hasta 3 correos separados por coma) y, si falta,
 * sale de SITIO_COTIZACIONES_EMAIL. El remitente y el SMTP salen de MAIL_* del .env.
 */
class Destinatarios
{
    /** @return array<int, string> */
    public static function resolver(): array
    {
        $crudo = (string) (SiteSetting::get('email_notificacion_cotizaciones') ?: config('sitio.cotizaciones.email_aviso'));

        $correos = array_filter(
            preg_split('/[\s,;]+/', $crudo, -1, PREG_SPLIT_NO_EMPTY) ?: [],
            fn (string $correo): bool => (bool) filter_var($correo, FILTER_VALIDATE_EMAIL),
        );

        return array_slice(array_values(array_unique($correos)), 0, 3);
    }
}
