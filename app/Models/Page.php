<?php

namespace App\Models;

use App\Models\Concerns\HasAuditing;
use App\Models\Concerns\ResolvesLocaleFields;
use App\Services\Cache\PublicContentCache;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

/**
 * Página institucional con jerarquía real (docs/01 §A.3, se abandona el `section`
 * fijo de IPG) y bloques de contenido (docs/02 §8).
 *
 * @property array<int, array<string, mixed>>|null $blocks
 */
#[Fillable([
    'created_by', 'updated_by', 'parent_id', 'seo_image_id',
    'title', 'slug', 'template', 'site_section', 'site', 'blocks',
    'seo_title', 'seo_description', 'canonical_url', 'is_indexable',
    'status', 'published_at', 'sort_order',
])]
class Page extends Model
{
    use HasAuditing, HasFactory, HasTranslations, ResolvesLocaleFields, SoftDeletes;

    /**
     * Páginas estructurales del sitio: cada una tiene su propia ruta y su propia pantalla en el panel (menú «Contenido»).
     * No son «páginas libres»: no se crean ni se borran, y no aparecen en «Páginas legales».
     */
    public const SECCIONES = ['inicio', 'productos', 'servicios', 'calidad', 'preguntas-frecuentes', 'ubicacion', 'contacto'];

    public array $translatable = ['title', 'seo_title', 'seo_description'];

    /**
     * Secciones que nunca pueden estar en borrador: la portada, y Contacto porque ahí llegan las cotizaciones (los
     * botones «Pedir cotización» de todo el sitio llevan a esa página).
     */
    public const SIEMPRE_PUBLICAS = ['inicio', 'contacto'];

    /**
     * Estas dos cosas viven en la petición actual (no en el contenedor de la aplicación): así nunca pasan de una visita
     * a la siguiente, tampoco en un servidor que mantenga la aplicación en memoria entre peticiones.
     */
    private const MEMO_VISTA_PREVIA = 'page.vista_previa';

    private const MEMO_BORRADORES = 'page.secciones_en_borrador';

    /**
     * Slugs de las secciones del sitio que están en borrador: no se ven en el sitio, ni en los menús ni en el sitemap.
     * La portada no tiene estado: siempre se muestra.
     *
     * @return array<int, string>
     */
    public static function seccionesEnBorrador(): array
    {
        $atributos = request()->attributes;

        if (! $atributos->has(self::MEMO_BORRADORES)) {
            $atributos->set(self::MEMO_BORRADORES, self::query()
                ->whereIn('slug', array_diff(self::SECCIONES, self::SIEMPRE_PUBLICAS))
                ->where('status', '!=', 'published')
                ->pluck('slug')
                ->all());
        }

        return $atributos->get(self::MEMO_BORRADORES);
    }

    /** ¿La persona con sesión puede ver los borradores en el sitio? Sí, quien tiene acceso a las páginas del panel. */
    public static function puedeVerBorradores(): bool
    {
        $usuario = auth()->user();

        return $usuario !== null && (bool) ($usuario->is_active ?? false) && $usuario->can('viewAny', self::class);
    }

    /**
     * ¿Se puede mostrar esta sección en el sitio? Si está publicada, para todos; si es un borrador, sólo en vista
     * previa para quien tiene sesión del panel (y entonces la página lleva un aviso y no se indexa).
     */
    public static function visible(string $slug): bool
    {
        if (self::seccionPublicada($slug)) {
            return true;
        }

        if (self::puedeVerBorradores()) {
            request()->attributes->set(self::MEMO_VISTA_PREVIA, true);

            return true;
        }

        return false;
    }

    /** Marca la petición como vista previa de un borrador (para el aviso, el noindex y que no se guarde en caché). */
    public static function marcarVistaPrevia(): void
    {
        request()->attributes->set(self::MEMO_VISTA_PREVIA, true);
    }

    public static function enVistaPrevia(): bool
    {
        return (bool) request()->attributes->get(self::MEMO_VISTA_PREVIA, false);
    }

    /** Una sección sin página cargada se considera publicada (se muestra con sus textos de siempre). */
    public static function seccionPublicada(string $slug): bool
    {
        return ! in_array($slug, self::seccionesEnBorrador(), true);
    }

    /** ¿Esta dirección (/servicios, /productos/chapas…) pertenece a una sección en borrador? */
    public static function direccionEnBorrador(string $url): bool
    {
        $ruta = trim((string) parse_url($url, PHP_URL_PATH), '/');

        foreach (self::seccionesEnBorrador() as $slug) {
            if ($ruta === $slug || str_starts_with($ruta, $slug.'/')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Invalida la caché de consulta pública (`PublicContentCache`, Fase 7,
     * docs/09-rendimiento.md §6) al guardar o borrar — se limpia tanto el slug
     * actual como el original si se editó el slug, para no dejar una entrada
     * vieja apuntando a contenido que ya no existe en esa URL.
     */
    protected static function booted(): void
    {
        static::saved(function (self $page) {
            request()->attributes->remove(self::MEMO_BORRADORES);
            PublicContentCache::forgetPageSlug($page->slug);
            PublicContentCache::forgetPageSlug($page->getOriginal('slug'));
        });

        static::deleted(function (self $page) {
            PublicContentCache::forgetPageSlug($page->slug);
        });
    }

    protected function casts(): array
    {
        return [
            'is_indexable' => 'boolean',
            'published_at' => 'datetime',
            'blocks' => 'array',
        ];
    }

    /**
     * Bloques de esta página en el idioma activo. Cada bloque guarda sus
     * campos de texto anidados por locale (ej. `title.es`, `title.it`).
     *
     * @return array<int, array<string, mixed>>
     */
    public function blocksForLocale(?string $locale = null): array
    {
        $locale ??= app()->getLocale();

        return collect($this->blocks ?? [])
            ->map(fn (array $block): array => [
                'type' => $block['type'],
                'data' => self::resolveLocaleFields(is_array($block['data'] ?? null) ? $block['data'] : [], $locale),
            ])
            ->all();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * @return BelongsTo<Media, $this>
     */
    /**
     * @return BelongsTo<Media, $this>
     */
    public function seoImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'seo_image_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function effectiveSeoTitle(?string $locale = null): ?string
    {
        return $this->getTranslation('seo_title', $locale ?? app()->getLocale())
            ?: $this->getTranslation('title', $locale ?? app()->getLocale());
    }

    /**
     * Ruta pública completa de la página, construida a partir de la cadena de
     * padres (ej. `institucion/historia`) — `slug` es único mundialmente pero
     * guarda un solo segmento por nivel (docs/06-frontend.md).
     */
    public function urlPath(): string
    {
        $segments = [];
        $node = $this;

        while ($node !== null) {
            array_unshift($segments, $node->slug);
            $node = $node->parent;
        }

        return implode('/', $segments);
    }

    /**
     * URL pública de una página de sección (ej. "vida-escolar") solo si ya está
     * publicada — evita que breadcrumbs de otras secciones (Comunicados,
     * Calendario, Galería) enlacen a una página de aterrizaje todavía en
     * borrador y den 404 al público.
     */
    public static function publishedUrl(string $slug): ?string
    {
        $isPublished = static::query()->where('slug', $slug)->where('status', 'published')->exists();

        return $isPublished ? url('/'.$slug) : null;
    }
}
