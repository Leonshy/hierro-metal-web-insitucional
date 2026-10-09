<?php

namespace App\Models;

use App\Models\Concerns\HasAuditing;
use App\Services\Html\HtmlSanitizer;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Una novedad del blog (/novedades/{slug}). Se ve en el sitio cuando está activa y su fecha de publicación ya llegó:
 * una fecha futura la deja programada. Mientras no haya ninguna visible, la sección entera (menú, portada y página)
 * desaparece: ver `Page::seccionesEnBorrador()`.
 *
 * @property string $titulo
 * @property string $slug
 * @property string $resumen
 * @property string $contenido
 * @property int|null $media_id
 * @property Carbon|null $publicada_en
 * @property bool $activo
 * @property string|null $seo_titulo
 * @property string|null $seo_descripcion
 * @property Carbon|null $updated_at
 * @property-read Media|null $media
 */
#[Fillable(['titulo', 'slug', 'resumen', 'contenido', 'media_id', 'publicada_en', 'activo', 'seo_titulo', 'seo_descripcion'])]
class Novedad extends Model
{
    use HasAuditing, HasFactory;

    protected $table = 'novedades';

    private const MEMO_HAY_PUBLICADAS = 'novedad.hay_publicadas';

    protected static function booted(): void
    {
        $olvidar = function (): void {
            request()->attributes->remove(self::MEMO_HAY_PUBLICADAS);
            Page::olvidarBorradores();
        };

        static::saved($olvidar);
        static::deleted($olvidar);
    }

    protected function casts(): array
    {
        return ['activo' => 'boolean', 'publicada_en' => 'datetime'];
    }

    /** El contenido admite HTML del editor: se sanea siempre al guardar (lista blanca de config/sitio.php). */
    protected function contenido(): Attribute
    {
        return Attribute::make(set: fn (?string $value) => app(HtmlSanitizer::class)->clean($value));
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    /** Visibles para el público: activas y con la fecha de publicación ya cumplida. */
    public function scopePublicadas(Builder $query): Builder
    {
        return $query->where('activo', true)->whereNotNull('publicada_en')->where('publicada_en', '<=', now());
    }

    /** Las más nuevas primero. */
    public function scopeRecientes(Builder $query): Builder
    {
        return $query->orderByDesc('publicada_en')->orderByDesc('id');
    }

    public function estaPublicada(): bool
    {
        return $this->activo && $this->publicada_en !== null && $this->publicada_en->lte(now());
    }

    /**
     * ¿Hay al menos una novedad visible? Decide si la sección existe para el público. Se calcula una vez por petición
     * (se consulta desde el menú, el pie, la portada y el mapa del sitio).
     */
    public static function hayPublicadas(): bool
    {
        $atributos = request()->attributes;

        if (! $atributos->has(self::MEMO_HAY_PUBLICADAS)) {
            $atributos->set(self::MEMO_HAY_PUBLICADAS, static::query()->publicadas()->exists());
        }

        return $atributos->get(self::MEMO_HAY_PUBLICADAS);
    }

    /**
     * Las últimas novedades visibles, con la foto cargada.
     *
     * @return Collection<int, static>
     */
    public static function ultimas(int $cantidad): Collection
    {
        return static::query()->with('media')->publicadas()->recientes()->limit($cantidad)->get();
    }

    /** Fecha para mostrar: «8 de octubre de 2026». */
    public function fecha(): string
    {
        return $this->publicada_en?->locale('es')->translatedFormat('j \d\e F \d\e Y') ?? '';
    }
}
