<?php

namespace App\Filament\Secciones\Concerns;

/** Para las pantallas de crear/editar de un módulo que vive dentro de una sección (ver PerteneceAUnaSeccion). */
trait VuelveALaSeccion
{
    protected function getRedirectUrl(): string
    {
        return static::getResource()::urlDeLaSeccion();
    }

    /** @return array<string, string> */
    public function getBreadcrumbs(): array
    {
        return [
            static::getResource()::urlDeLaSeccion() => static::getResource()::etiquetaDeLaSeccion(),
            $this->getBreadcrumb(),
        ];
    }
}
