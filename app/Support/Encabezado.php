<?php

namespace App\Support;

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
        public readonly ?string $imagen = null,
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
            imagen: ($hero['image'] ?? null) ?: null,
        );
    }
}
