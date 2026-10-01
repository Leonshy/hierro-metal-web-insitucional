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
        abort_unless(Page::visible('calidad'), 404);

        $pagina = Page::query()->where('slug', 'calidad')->firstOrFail();

        $textos = collect($pagina->blocksForLocale())
            ->where('type', 'texto')
            ->map(fn (array $b): string => (string) ($b['data']['content'] ?? ''))
            ->filter(fn (string $html): bool => trim(strip_tags($html)) !== '')
            ->values();

        // El primer texto es la introducción (va antes de los compromisos); los demás, después. Una página vieja con un
        // solo texto largo se parte en su primer subtítulo, como antes.
        if ($textos->count() === 1) {
            $html = (string) $textos->first();
            $corte = preg_match('/<h2\b/i', $html, $m, PREG_OFFSET_CAPTURE) ? $m[0][1] : strlen($html);
            [$introduccion, $resto] = [substr($html, 0, $corte), substr($html, $corte)];
        } else {
            [$introduccion, $resto] = [(string) $textos->first(), $textos->slice(1)->implode('')];
        }

        return view('calidad', [
            'encabezado' => Encabezado::de('calidad'),
            'pagina' => $pagina,
            'introduccion' => $introduccion,
            'resto' => $resto,
            'compromisos' => Compromiso::query()->activos()->ordenados()->get(),
        ]);
    }
}
