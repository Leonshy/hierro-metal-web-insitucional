<?php

namespace App\Models;

use App\Models\Concerns\HasAuditing;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Configuración global clave/valor — patrón copiado tal cual de IPG
 * (docs/01-analisis-descubrimiento.md §A.4).
 */
#[Fillable(['key', 'value', 'type', 'group', 'label', 'description'])]
class SiteSetting extends Model
{
    use HasAuditing;

    public const CACHE_PREFIX = 'site_setting:';

    /** Todos los ajustes juntos en una sola entrada de caché (ver `todos()`). */
    private const CACHE_TODOS = self::CACHE_PREFIX.'__todos';

    private const MEMO = 'site_settings.todos';

    protected static function booted(): void
    {
        // Cualquier cambio (panel, seeders, código) invalida la lectura; no hace falta acordarse de hacerlo a mano.
        static::saved(fn () => self::olvidar());
        static::deleted(fn () => self::olvidar());
    }

    public static function olvidar(): void
    {
        Cache::forget(self::CACHE_TODOS);
        app()->forgetInstance(self::MEMO);
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $todos = self::todos();

        return array_key_exists($key, $todos) ? $todos[$key] : $default;
    }

    public static function set(string $key, mixed $value, string $type = 'text', string $group = 'general'): self
    {
        // El evento `saved` se ocupa de invalidar la lectura.
        return self::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'group' => $group],
        );
    }

    /**
     * Una página lee 20–30 ajustes (contacto, redes, aviso…). Con la caché en base de datos, pedir cada uno
     * por separado cuesta una consulta cada vez: acá se leen todos juntos, una sola vez por petición.
     *
     * @return array<string, mixed>
     */
    private static function todos(): array
    {
        if (! app()->bound(self::MEMO)) {
            app()->instance(self::MEMO, Cache::remember(self::CACHE_TODOS, 3600, fn (): array => self::query()->get()
                ->mapWithKeys(fn (self $ajuste): array => [$ajuste->key => $ajuste->type === 'boolean' ? (bool) $ajuste->value : $ajuste->value])
                ->all()));
        }

        return app(self::MEMO);
    }

    /**
     * Segundo idioma: sólo si está en `sitio.locales` (apagado en Hierro Metal) y activado
     * en la configuración. Con locale fijo `es`, los controles de idioma no se muestran.
     */
    public static function italianEnabled(): bool
    {
        return in_array('it', config('sitio.locales', ['es']), true)
            && (bool) self::get('italian_enabled', false);
    }

    /**
     * Mantenimiento del sitio público — no toca el panel (Fase 10, pedido del
     * cliente): es un interruptor de contenido, no el `php artisan down` de
     * infraestructura. `App\Http\Middleware\PublicMaintenanceMode` lo lee.
     */
    public static function maintenanceModeEnabled(): bool
    {
        return (bool) self::get('maintenance_mode', false);
    }
}
