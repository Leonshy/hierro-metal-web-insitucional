<?php

namespace App\Filament\Secciones\Concerns;

use Filament\Pages\Page;

/**
 * Para los módulos que ya no tienen entrada propia en el menú: viven dentro de una sección. Sus pantallas de
 * crear y editar vuelven a esa sección al terminar, y el recurso no aparece en el menú lateral.
 */
trait PerteneceAUnaSeccion
{
    /** @return class-string<Page> */
    abstract protected static function seccion(): string;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function urlDeLaSeccion(): string
    {
        return static::seccion()::getUrl();
    }

    public static function etiquetaDeLaSeccion(): string
    {
        return (string) static::seccion()::getNavigationLabel();
    }
}
