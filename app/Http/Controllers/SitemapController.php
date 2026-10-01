<?php

namespace App\Http\Controllers;

use App\Models\Compromiso;
use App\Models\Familia;
use App\Models\Faq;
use App\Models\Horario;
use App\Models\Linea;
use App\Models\Page;
use App\Models\Paso;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

/**
 * `sitemap.xml` dinámico (docs/08-seo.md §4) — se genera en cada petición a
 * partir de la base, con `lastmod` real (`updated_at`). Excluye borradores,
 * archivadas y todo lo marcado `noindex`. Sin caché de archivo: el volumen
 * del sitio no lo justifica (spatie/laravel-sitemap ya está entre los
 * paquetes de referencia, CLAUDE.md §3).
 */
class SitemapController extends Controller
{
    /**
     * Módulos del panel cuyo contenido se muestra en cada página: si se edita uno, la página cambió aunque
     * su propio registro no (por ejemplo, una familia nueva cambia /productos).
     *
     * @var array<string, array<int, class-string<Model>>>
     */
    private const DEPENDENCIAS = [
        'productos' => [Familia::class, Linea::class],
        'servicios' => [Servicio::class, Paso::class],
        'preguntas-frecuentes' => [Faq::class],
        'calidad' => [Compromiso::class],
        'ubicacion' => [Horario::class],
    ];

    public function index(): Responsable
    {
        $sitemap = Sitemap::create()
            ->add(
                Url::create(url('/'))
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(1.0)
            );

        Page::query()
            ->where('status', 'published')
            ->where('is_indexable', true)
            ->get()
            ->each(function (Page $page) use ($sitemap): void {
                $sitemap->add(
                    Url::create(url('/'.$page->urlPath()))
                        ->setLastModificationDate($this->ultimaModificacion($page->updated_at, self::DEPENDENCIAS[$page->slug] ?? []))
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.8)
                );
            });

        // Fichas de familia: cambian cuando se edita la familia o cualquiera de sus líneas.
        Familia::query()->activos()->ordenados()->get()->each(function (Familia $familia) use ($sitemap): void {
            $sitemap->add(
                Url::create(route('productos.show', $familia->slug))
                    ->setLastModificationDate($this->ultimaModificacion($familia->updated_at, [], $familia->lineas()->max('updated_at')))
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.8)
            );
        });

        $sitemap->add(Url::create(route('contact.show'))->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY));

        return $sitemap;
    }

    /**
     * La fecha más reciente entre la propia y la de los módulos que alimentan la página.
     *
     * @param  array<int, class-string<Model>>  $modelos
     */
    private function ultimaModificacion(?Carbon $propia, array $modelos, ?string $otra = null): Carbon
    {
        return collect([$propia, $otra ? Carbon::parse($otra) : null])
            ->merge(collect($modelos)->map(fn (string $m) => ($f = $m::query()->max('updated_at')) ? Carbon::parse($f) : null))
            ->filter()
            ->max() ?? now();
    }
}
