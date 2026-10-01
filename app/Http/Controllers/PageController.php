<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\Cache\PublicContentCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $slug): View|RedirectResponse
    {
        // La portada vive en «/»: la página «inicio» sólo guarda sus textos y no se sirve por su cuenta.
        if ($slug === 'inicio') {
            return redirect('/', 301);
        }

        // Caché de consulta (no de respuesta HTTP completa, ver el porqué en
        // PublicContentCache) — invalidada al guardar/borrar desde el panel.
        $page = PublicContentCache::rememberPageBySlug(
            $slug,
            fn () => Page::query()
                ->with('seoImage')
                ->where('slug', $slug)
                ->where('status', 'published')
                ->first()
        );

        abort_if($page === null, 404);

        $breadcrumbs = $this->breadcrumbsFor($page);
        $blocks = $page->blocksForLocale();

        return view('pages.show', compact('page', 'breadcrumbs', 'blocks'));
    }

    /** @return array<int, array{label: string, url: ?string}> */
    private function breadcrumbsFor(Page $page): array
    {
        $trail = [];
        $node = $page;

        while ($node !== null) {
            $trail[] = ['label' => $node->title, 'url' => $node->status === 'published' ? '/'.$node->slug : null];
            $node = $node->parent;
        }

        return array_reverse($trail);
    }
}
