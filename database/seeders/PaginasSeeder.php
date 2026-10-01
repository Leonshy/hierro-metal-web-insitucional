<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Services\Html\HtmlSanitizer;
use Database\Seeders\Concerns\LeeContenido;
use Illuminate\Database\Seeder;

/**
 * Páginas de texto (Calidad, Privacidad), los textos de encabezado de cada plantilla y los menús.
 *
 * La política de privacidad se carga como BORRADOR: no se publica sin la revisión del asesor legal del cliente
 * (cita la Ley 6534/2020 y falta su sección de cookies por D5; ver docs/07 §4).
 */
class PaginasSeeder extends Seeder
{
    use LeeContenido;

    public function run(): void
    {
        $limpiador = app(HtmlSanitizer::class);

        foreach ($this->contenido('paginas') as $p) {
            $bloques = [$this->hero($p['titulo'], $p['bajada'] ?? null)];
            $bloques[] = ['type' => 'texto', 'data' => ['content' => ['es' => $limpiador->clean($p['html'])]]];

            $this->pagina($p['slug'], $p['titulo'], $bloques, $p['estado'], $p['indexable'], $p['seo_titulo'], $p['seo_descripcion']);
        }

        foreach ($this->contenido('encabezados') as $e) {
            $this->pagina(
                $e['slug'], $e['titulo'], [$this->hero($e['titulo'], $e['bajada'], $e['cta_label'], $e['cta_url'])],
                'published', $e['indexable'], $e['seo_titulo'], $e['seo_descripcion'],
                canonical: $e['slug'] === 'inicio' ? url('/') : null,
            );
        }

        foreach ($this->contenido('menus') as $clave => $menu) {
            $registro = Menu::query()->firstOrCreate(['key' => $clave], ['name' => $menu['nombre']]);

            foreach ($menu['items'] as $i => $item) {
                if (! MenuItem::query()->where('menu_id', $registro->id)->where('url', $item['url'])->exists()) {
                    MenuItem::query()->create([
                        'menu_id' => $registro->id, 'label' => ['es' => $item['label']], 'url' => $item['url'],
                        'sort_order' => $i + 1, 'open_in_new_tab' => false, 'is_active' => true,
                    ]);
                }
            }
        }
    }

    /** @return array{type: string, data: array<string, mixed>} */
    private function hero(string $titulo, ?string $bajada, ?string $etiquetaBoton = null, ?string $urlBoton = null): array
    {
        return ['type' => 'hero', 'data' => array_filter([
            'title' => ['es' => $titulo],
            'subtitle' => $bajada ? ['es' => $bajada] : null,
            'cta_label' => $etiquetaBoton ? ['es' => $etiquetaBoton] : null,
            'cta_url' => $urlBoton,
        ])];
    }

    private function pagina(string $slug, string $titulo, array $bloques, string $estado, bool $indexable, string $seoTitulo, string $seoDescripcion, ?string $canonical = null): void
    {
        Page::query()->firstOrCreate(['slug' => $slug], [
            'title' => ['es' => $titulo],
            'site_section' => 'general',
            'blocks' => $bloques,
            'status' => $estado,
            'published_at' => $estado === 'published' ? now() : null,
            'is_indexable' => $indexable,
            'canonical_url' => $canonical,
            'seo_title' => ['es' => $seoTitulo],
            'seo_description' => ['es' => $seoDescripcion],
        ]);
    }
}
