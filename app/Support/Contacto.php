<?php

namespace App\Support;

use App\Models\SiteSetting;

/**
 * Datos de contacto del sitio. Se editan en el panel (Configuración): las plantillas nunca llevan
 * teléfonos, correos ni direcciones escritos a mano, así un cambio se hace una sola vez.
 */
final class Contacto
{
    private static function dato(string $clave, string $respaldo = ''): string
    {
        return trim((string) (SiteSetting::get($clave) ?: $respaldo));
    }

    /** Teléfono tal como se muestra: «+595 981 320 675». */
    public static function telefono(): string
    {
        return self::dato('contact_phone', '+595 981 320 675');
    }

    /** WhatsApp y llamadas: sólo dígitos con el código del país. */
    public static function whatsappNumero(): string
    {
        return preg_replace('/\D+/', '', self::dato('whatsapp_number', '595981320675')) ?? '';
    }

    public static function telefonoHref(): string
    {
        return 'tel:+'.self::whatsappNumero();
    }

    /** Enlace de WhatsApp con el mensaje precargado (docs/07 §3.6). */
    public static function whatsappUrl(?string $mensaje = null): string
    {
        return 'https://wa.me/'.self::whatsappNumero().($mensaje ? '?text='.rawurlencode($mensaje) : '');
    }

    public static function email(): string
    {
        return self::dato('contact_email');
    }

    public static function direccionCorta(): string
    {
        return self::dato('direccion_corta', 'Pedro Getto esq. Cadete Sisa');
    }

    public static function direccionLarga(): string
    {
        return self::dato('direccion_larga', 'Pedro Getto esquina Cadete Sisa, Fernando de la Mora, departamento Central, Paraguay');
    }

    public static function ciudad(): string
    {
        return self::dato('ciudad', 'Fernando de la Mora');
    }

    public static function mapsUrl(): string
    {
        return self::dato('maps_url');
    }

    /** Mapa incrustable: el configurado o, si falta, uno armado con la dirección (no requiere clave de API). */
    public static function mapsEmbedUrl(): string
    {
        return self::dato('google_maps_embed_url') ?: 'https://www.google.com/maps?q='.rawurlencode(self::direccionLarga()).'&output=embed';
    }

    public static function instagram(): string
    {
        return self::dato('social_instagram_url');
    }

    public static function facebook(): string
    {
        return self::dato('social_facebook_url');
    }

    public static function avisoNumeroUnico(): string
    {
        return self::dato('aviso_numero_unico');
    }

    public static function descripcionCorta(): string
    {
        return self::dato('descripcion_corta', 'Importación y venta de materiales de construcción metálicos y metalúrgicos.');
    }
}
