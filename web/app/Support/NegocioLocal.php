<?php

namespace App\Support;

use App\Models\Horario;
use App\Models\SiteSetting;

/**
 * Datos estructurados (JSON-LD) del negocio para buscadores: dirección, teléfono, horarios y redes, todo desde el
 * panel. Los campos vacíos se omiten en lugar de escribir cadenas vacías (el validador de schema.org las marca).
 * No se inventa nada: la geolocalización sólo sale si el cliente cargó latitud y longitud.
 */
final class NegocioLocal
{
    private const DIAS = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];

    /** @return array<string, mixed> */
    public static function schema(): array
    {
        $lat = SiteSetting::get('geo_latitud');
        $lng = SiteSetting::get('geo_longitud');
        $redes = array_values(array_filter([Contacto::facebook(), Contacto::instagram()]));

        return self::sinVacios([
            '@context' => 'https://schema.org',
            '@type' => 'HardwareStore',
            'name' => config('sitio.seo.organization_name'),
            'legalName' => config('sitio.seo.organization_legal_name'),
            'description' => Contacto::descripcionCorta(),
            'url' => url('/'),
            'logo' => asset('images/logo-hierro-metal.svg'),
            'image' => self::absoluta(config('sitio.seo.default_og_image') ?: 'images/logo-hierro-metal.svg'),
            'telephone' => '+'.Contacto::whatsappNumero(),
            'email' => Contacto::email(),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => Contacto::direccionCorta(),
                'addressLocality' => Contacto::ciudad(),
                'addressRegion' => (string) SiteSetting::get('departamento', 'Central'),
                'addressCountry' => 'PY',
            ],
            'geo' => is_numeric($lat) && is_numeric($lng)
                ? ['@type' => 'GeoCoordinates', 'latitude' => (float) $lat, 'longitude' => (float) $lng]
                : null,
            'hasMap' => Contacto::mapsUrl(),
            'areaServed' => ['@type' => 'Country', 'name' => 'Paraguay'],
            'openingHoursSpecification' => self::horarios(),
            'sameAs' => $redes,
        ]);
    }

    /** Los buscadores no resuelven rutas relativas. */
    private static function absoluta(string $ruta): string
    {
        return preg_match('#^https?://#i', $ruta) ? $ruta : asset($ruta);
    }

    /** @return array<int, array<string, mixed>> */
    private static function horarios(): array
    {
        return Horario::query()->activos()->ordenados()->get()
            ->reject(fn (Horario $h): bool => $h->cerrado || ! $h->abre || ! $h->cierra)
            ->map(fn (Horario $h): array => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => collect($h->dias)->map(fn (int $d): ?string => self::DIAS[$d] ?? null)->filter()->values()->all(),
                'opens' => substr($h->abre, 0, 5),
                'closes' => substr($h->cierra, 0, 5),
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $datos
     * @return array<string, mixed>
     */
    private static function sinVacios(array $datos): array
    {
        return collect($datos)
            ->map(fn ($valor) => is_array($valor) && ! array_is_list($valor) ? self::sinVacios($valor) : $valor)
            ->filter(fn ($valor) => $valor !== null && $valor !== '' && $valor !== [])
            ->all();
    }
}
