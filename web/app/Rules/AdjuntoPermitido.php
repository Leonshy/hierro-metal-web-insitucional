<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

/**
 * Valida un adjunto de cotización por su contenido real, no sólo por la extensión (regla 10).
 *
 * Lista blanca: pdf, jpg, jpeg, png, webp, dwg, dxf, xlsx. Los formatos de imagen, PDF y xlsx se
 * verifican por su firma y por `finfo`. DWG y DXF no tienen un MIME fiable (los servidores los
 * devuelven como `application/octet-stream` o `text/plain`), así que se reconocen por su cabecera:
 * los DWG empiezan con «AC» y la versión, los DXF con la sección «0 SECTION» o «AutoCAD Binary DXF».
 * Nunca se ejecutan ni se muestran en línea.
 */
class AdjuntoPermitido implements ValidationRule
{
    public const MENSAJE_TIPO = 'Ese tipo de archivo no se puede adjuntar. Usá PDF, JPG, PNG, WEBP, DWG, DXF o XLSX.';

    public const MENSAJE_PESO = 'El archivo pesa más de 10 MB. Probá comprimirlo o mandanos el plano por WhatsApp.';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile || ! $value->isValid()) {
            $fail('No pudimos recibir ese archivo. Probá de nuevo.');

            return;
        }

        if ($value->getSize() > (int) config('sitio.cotizaciones.adjunto_max_kb') * 1024) {
            $fail(self::MENSAJE_PESO);

            return;
        }

        $extension = strtolower($value->getClientOriginalExtension());

        if (! in_array($extension, config('sitio.cotizaciones.adjuntos_permitidos'), true) || ! self::contenidoCoincide($value, $extension)) {
            $fail(self::MENSAJE_TIPO);
        }
    }

    public static function contenidoCoincide(UploadedFile $archivo, string $extension): bool
    {
        $cabecera = (string) file_get_contents($archivo->getRealPath(), false, null, 0, 64);
        $mime = (string) (new \finfo(FILEINFO_MIME_TYPE))->file($archivo->getRealPath());

        return match ($extension) {
            'pdf' => str_starts_with($cabecera, '%PDF-'),
            'jpg', 'jpeg' => $mime === 'image/jpeg' && @getimagesize($archivo->getRealPath()) !== false,
            'png' => $mime === 'image/png' && @getimagesize($archivo->getRealPath()) !== false,
            'webp' => $mime === 'image/webp' && @getimagesize($archivo->getRealPath()) !== false,
            'xlsx' => str_starts_with($cabecera, 'PK') && in_array($mime, ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip'], true),
            'dwg' => (bool) preg_match('/^AC(1\.|2\.|[0-9]{4})/', $cabecera),
            'dxf' => str_starts_with($cabecera, 'AutoCAD Binary DXF') || (bool) preg_match('/^\xEF?\xBB?\xBF?\s*0\s+SECTION/s', $cabecera),
            default => false,
        };
    }
}
