<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;

/**
 * Catálogo PDF vigente. El cliente reemplaza el archivo desde el panel y el enlace público
 * (`/catalogo.pdf`) no cambia. Sin archivo cargado, los botones de descarga no se muestran.
 */
final class Catalogo
{
    public const DISCO = 'local';

    public static function ruta(): ?string
    {
        $ruta = SiteSetting::get('catalogo_path');

        return filled($ruta) && Storage::disk(self::DISCO)->exists($ruta) ? $ruta : null;
    }

    public static function disponible(): bool
    {
        return self::ruta() !== null;
    }

    public static function url(): ?string
    {
        return self::disponible() ? route('catalogo') : null;
    }

    public static function textoBoton(): string
    {
        return (string) (SiteSetting::get('catalogo_texto_boton') ?: 'Descargar catálogo');
    }
}
