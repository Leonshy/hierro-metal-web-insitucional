<?php

namespace App\Http\Controllers;

use App\Models\Compromiso;
use App\Models\Page;
use App\Support\Encabezado;
use Illuminate\View\View;

/**
 * Política de calidad. El texto largo es la página «calidad» del panel; los compromisos son su módulo.
 * El texto se parte en el primer subtítulo: lo anterior es la introducción y los compromisos van justo después.
 */
class CalidadController extends Controller
{
    public function index(): View
    {
        $pagina = Page::query()->where('slug', 'calidad')->where('status', 'published')->firstOrFail();

        $html = collect($pagina->blocksForLocale())->where('type', 'texto')->map(fn (array $b): string => (string) ($b['data']['content'] ?? ''))->implode('');
        $corte = preg_match('/<h2\b/i', $html, $m, PREG_OFFSET_CAPTURE) ? $m[0][1] : strlen($html);

        return view('calidad', [
            'encabezado' => Encabezado::de('calidad'),
            'pagina' => $pagina,
            'introduccion' => substr($html, 0, $corte),
            'resto' => substr($html, $corte),
            'compromisos' => Compromiso::query()->activos()->ordenados()->get(),
        ]);
    }
}
