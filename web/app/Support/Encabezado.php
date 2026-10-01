<?php

namespace App\Support;

use App\Models\Media;
use App\Models\Page;

/**
 * Textos de encabezado de una plantilla (título, bajada, botón y SEO). Viven como páginas editables
 * en el panel (docs/03 §4): no están escritos a mano en las vistas.
 */
final class Encabezado
{
    public function __construct(
        public readonly string $titulo,
        public readonly ?string $bajada = null,
        public readonly ?string $ctaTexto = null,
        public readonly ?string $ctaUrl = null,
        public readonly ?string $seoTitulo = null,
        public readonly ?string $seoDescripcion = null,
        public readonly ?int $mediaId = null,
        /** @var array<string, mixed> Todos los campos del bloque hero, ya en español (insignia, nota, textos de bloques…). */
        public readonly array $datos = [],
    ) {}

    public static function de(string $slug): self
    {
        $pagina = Page::query()->where('slug', $slug)->first();

        if (! $pagina) {
            return new self('');
        }

        $hero = collect($pagina->blocksForLocale())->firstWhere('type', 'hero')['data'] ?? [];

        return new self(
            titulo: (string) ($hero['title'] ?? $pagina->getTranslation('title', 'es')),
            bajada: $hero['subtitle'] ?? null,
            ctaTexto: $hero['cta_label'] ?? null,
            ctaUrl: $hero['cta_url'] ?? null,
            seoTitulo: $pagina->getTranslation('seo_title', 'es', false) ?: null,
            seoDescripcion: $pagina->getTranslation('seo_description', 'es', false) ?: null,
            mediaId: ($hero['media_id'] ?? null) ?: null,
            datos: $hero,
        );
    }

    /** Foto del hero, de la biblioteca de medios (con sus variantes responsivas). */
    public function media(): ?Media
    {
        return $this->mediaId ? Media::query()->find($this->mediaId) : null;
    }

    /** Un campo extra del encabezado editable desde el panel; si está vacío se usa el texto por defecto. */
    public function dato(string $clave, ?string $porDefecto = null): ?string
    {
        $valor = $this->datos[$clave] ?? null;

        return is_string($valor) && trim($valor) !== '' ? $valor : $porDefecto;
    }
}
