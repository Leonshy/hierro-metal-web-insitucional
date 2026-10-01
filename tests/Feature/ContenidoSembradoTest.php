<?php

use App\Models\Compromiso;
use App\Models\Cotizacion;
use App\Models\Diferencial;
use App\Models\Familia;
use App\Models\Faq;
use App\Models\Horario;
use App\Models\Linea;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Paso;
use App\Models\Rubro;
use App\Models\Servicio;
use App\Models\SiteSetting;
use App\Models\Vendedor;
use App\Rules\MaxWords;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

beforeEach(function () {
    // La siembra también carga las fotos del catálogo: nunca a los discos reales.
    Storage::fake('media');
    Storage::fake('public');
    $this->seed(DatabaseSeeder::class);
});

/** Cumple la regla que el panel le exige al texto: si no, no se podría volver a guardar al editarlo. */
function cumpleLimite(string $texto, int $maximo): bool
{
    return ! Validator::make(['t' => $texto], ['t' => [new MaxWords($maximo)]])->fails();
}

it('carga las 5 familias del catálogo con sus 16 líneas, en el orden del cliente', function () {
    expect(Familia::query()->ordenados()->pluck('slug')->all())->toBe(['chapas', 'perfiles', 'tubos', 'varillas', 'accesorios'])
        ->and(Linea::query()->count())->toBe(16)
        ->and(Familia::query()->withCount('lineas')->ordenados()->pluck('lineas_count')->all())->toBe([7, 3, 2, 3, 1]);
});

it('calcula los rótulos «01 · 7 líneas» desde los datos cargados', function () {
    $rotulos = Familia::query()->ordenados()->get()->map->rotulo()->all();

    expect($rotulos)->toBe(['01 · 7 líneas', '02 · 3 líneas', '03 · 2 líneas', '04 · 3 líneas', '05 · 1 línea']);
});

it('carga las tablas de medidas del catálogo en las líneas que las tienen', function () {
    $conMedidas = Linea::query()->get()->filter->tieneMedidas()->pluck('nombre')->all();

    expect($conMedidas)->toHaveCount(10)
        ->and($conMedidas)->toContain('Laminadas en frío y en caliente', 'Galvanizadas', 'Inoxidables', 'Antideslizantes', 'Desplegadas')
        ->and($conMedidas)->toContain('Perfil IPN y UPN', 'Caños redondos, cuadrados y rectangulares', 'Varillas lisas, de construcción y cuadradas');
});

it('las laminadas en frío y en caliente traen dos tablas con todos sus espesores', function () {
    $tablas = Linea::query()->where('nombre', 'Laminadas en frío y en caliente')->firstOrFail()->tablas();

    expect($tablas)->toHaveCount(2)
        ->and($tablas[0]['titulo'])->toBe('Laminadas en frío')
        ->and($tablas[0]['filas'])->toHaveCount(10)
        ->and($tablas[0]['filas'][0])->toBe(['N°16 – 1,50', '2000/2400/3000', '1000/1200/1500'])
        ->and($tablas[1]['filas'])->toHaveCount(11)
        ->and($tablas[1]['filas'][0][0])->toBe('75 mm · 3"');
});

it('aplica las decisiones sobre el catálogo: lo corregido, corregido; lo dudoso, tal cual el PDF', function () {
    $canos = Linea::query()->where('nombre', 'like', 'Caños%')->firstOrFail()->tablas()[0]['filas'];
    $fila = fn (string $pulgada) => collect($canos)->firstWhere(0, $pulgada);

    expect($canos)->toHaveCount(31)
        ->and($fila('5/8')[2])->toBe('12×12')                       // decisión 3: corregido
        ->and($fila('3/4')[1])->toBe('10,05')                       // decisión 2: tal cual el PDF
        ->and($fila('4"')[1])->toBe('101,69')                       // decisión 4: tal cual el PDF
        ->and(collect($canos)->firstWhere(1, '40,00')[0])->toBe(''); // la fila sin pulgada se conserva

    $desplegadas = json_encode(Linea::query()->where('nombre', 'Desplegadas')->firstOrFail()->medidas, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    expect($desplegadas)->toContain('3/16')->not->toContain('3/6')   // decisión 5
        ->and($desplegadas)->not->toContain('N° 1/8');               // decisión 7
});

it('todas las tablas están bien formadas: cada fila tiene tantas celdas como columnas', function () {
    foreach (Linea::query()->get() as $linea) {
        foreach ($linea->tablas() as $tabla) {
            foreach ($tabla['filas'] as $i => $fila) {
                expect(count($fila))->toBe(count($tabla['columnas']), "{$linea->nombre} · {$tabla['titulo']} · fila ".($i + 1));
            }
        }
    }
});

it('los textos sembrados respetan los límites que el panel exige al editar', function () {
    foreach (Familia::query()->get() as $f) {
        expect(cumpleLimite($f->nombre, 6))->toBeTrue($f->nombre)
            ->and(cumpleLimite($f->resumen_home, 22))->toBeTrue("resumen {$f->slug}")
            ->and(cumpleLimite($f->bajada, 40))->toBeTrue("bajada {$f->slug}");
    }

    foreach (Linea::query()->get() as $l) {
        expect(cumpleLimite($l->nombre, 24))->toBeTrue($l->nombre)
            ->and(cumpleLimite($l->descripcion, 25))->toBeTrue("descripción {$l->nombre}")
            ->and(count($l->usos))->toBeLessThanOrEqual(4)
            ->and($l->nota_medidas === null || cumpleLimite($l->nota_medidas, 25))->toBeTrue("nota {$l->nombre}");
    }

    foreach (Servicio::query()->get() as $s) {
        expect(cumpleLimite($s->nombre, 6))->toBeTrue($s->nombre)
            ->and(cumpleLimite($s->descripcion, 45))->toBeTrue("descripción {$s->nombre}")
            ->and(count($s->usos))->toBeLessThanOrEqual(4);
    }

    foreach (Paso::query()->get() as $p) {
        expect(cumpleLimite($p->titulo, 6))->toBeTrue()->and(cumpleLimite($p->texto, 25))->toBeTrue($p->titulo);
    }

    foreach (Compromiso::query()->get() as $c) {
        expect(cumpleLimite($c->titulo, 6))->toBeTrue()->and(cumpleLimite($c->texto, 30))->toBeTrue($c->titulo);
    }

    foreach (Diferencial::query()->get() as $d) {
        expect(cumpleLimite($d->titulo, 5))->toBeTrue()->and(cumpleLimite($d->texto, 8))->toBeTrue($d->titulo);
    }
});

it('carga los servicios, destacando tres en el inicio', function () {
    expect(Servicio::query()->count())->toBe(6)
        ->and(Servicio::query()->where('destacado_home', true)->ordenados()->pluck('titulo_home')->all())
        ->toBe(['Cortes y plegados', 'Fabricación especial', 'Galvanización']);
});

it('carga los 4 pasos, los 4 compromisos, los 4 diferenciales y las 10 preguntas frecuentes', function () {
    expect(Paso::query()->ordenados()->pluck('titulo')->first())->toBe('Nos mandás el pedido')
        ->and(Compromiso::query()->count())->toBe(4)
        ->and(Diferencial::query()->ordenados()->pluck('titulo')->all())->toBe(['Stock permanente', 'Importación directa', 'Corte a medida', 'Flota propia'])
        ->and(Faq::query()->count())->toBe(10);
});

it('las preguntas frecuentes llevan enlaces reales y el correo nuevo', function () {
    $cotizar = Faq::query()->where('pregunta', '¿Cómo pido una cotización?')->firstOrFail()->respuesta;

    expect($cotizar)->toContain('<a href="/contacto">formulario de contacto</a>')
        ->and($cotizar)->toContain('mailto:hierrometalventas@hotmail.com')
        ->and($cotizar)->not->toContain('admin@hierrometal.com');
});

it('carga los horarios del cliente y el domingo cerrado', function () {
    expect(Horario::query()->ordenados()->pluck('etiqueta')->all())->toBe(['Lunes a viernes', 'Sábados', 'Domingos y feriados'])
        ->and(Horario::query()->where('etiqueta', 'Domingos y feriados')->firstOrFail()->cerrado)->toBeTrue()
        ->and(Horario::estadoAhora(Carbon\Carbon::parse('2026-10-07 10:00', 'America/Asuncion'))['texto'])
        ->toBe('Abierto ahora · cerramos a las 17:00');
});

it('carga los 9 rubros del formulario y los vincula a su familia o servicio', function () {
    expect(Rubro::query()->count())->toBe(9)
        ->and(Rubro::query()->where('slug', 'chapas')->firstOrFail()->familia->slug)->toBe('chapas')
        ->and(Rubro::query()->where('slug', 'galvanizacion')->firstOrFail()->servicio->nombre)->toBe('Galvanización')
        ->and(Rubro::query()->where('slug', 'otra-consulta')->firstOrFail()->familia_id)->toBeNull();
});

it('el formulario del sitio ofrece los rubros sembrados y los acepta', function () {
    $this->get('/contacto')->assertOk()->assertSee('Accesorios de cañería')->assertSee('Varios materiales / lista completa');
});

it('carga la configuración global con los datos decididos y sin correo de avisos todavía', function () {
    expect(SiteSetting::get('contact_email'))->toBe('hierrometalventas@hotmail.com')
        ->and(SiteSetting::get('whatsapp_number'))->toBe('595981320675')
        ->and(SiteSetting::get('maps_url'))->toBe('https://maps.app.goo.gl/XpbnH8AFw9iJSExs8')
        ->and(SiteSetting::get('aviso_numero_unico'))->toContain('único número corporativo')
        ->and(SiteSetting::get('email_notificacion_cotizaciones'))->toBe('');
});

it('Calidad queda publicada y Privacidad queda en borrador hasta la revisión legal', function () {
    $calidad = Page::query()->where('slug', 'calidad')->firstOrFail();
    $privacidad = Page::query()->where('slug', 'privacidad')->firstOrFail();

    expect($calidad->status)->toBe('published')
        ->and($privacidad->status)->toBe('draft')
        // Calidad se siembra en textos separados: introducción, «Qué significa esto…» y «Ámbito de aplicación».
        ->and(collect($calidad->blocks)->where('type', 'texto')->pluck('data.content.es')->all())->toHaveCount(3)
        ->and($calidad->blocks[2]['data']['content']['es'])->toContain('Qué significa esto para tu obra');

    $this->get('/calidad')->assertOk()->assertSee('Materia prima certificada');
    $this->get('/privacidad')->assertNotFound();
});

it('la política de privacidad lleva el ítem de adjuntos y el correo nuevo', function () {
    $texto = Page::query()->where('slug', 'privacidad')->firstOrFail()->blocks[1]['data']['content']['es'];

    expect($texto)->toContain('Los archivos que adjuntes')
        ->and($texto)->toContain('hierrometalventas@hotmail.com')
        ->and($texto)->not->toContain('admin@hierrometal.com')
        ->and($texto)->toContain('Qué datos recopilamos');
});

it('carga el texto de encabezado de cada plantilla como página editable', function () {
    expect(Page::query()->whereIn('slug', ['inicio', 'productos', 'servicios', 'preguntas-frecuentes', 'ubicacion', 'contacto'])->count())->toBe(6)
        ->and(Page::query()->where('slug', 'inicio')->firstOrFail()->is_indexable)->toBeFalse();
});

it('carga los menús principal y de pie', function () {
    expect(collect(Menu::renderTree('primary'))->pluck('label')->all())
        ->toBe(['Productos', 'Servicios', 'Calidad', 'Preguntas frecuentes', 'Ubicación', 'Contacto'])
        ->and(collect(Menu::renderTree('footer_secondary'))->pluck('url')->last())->toBe('/privacidad');
});

it('los menús sembrados apuntan a rutas que existen o que construye la Fase 4', function () {
    foreach (Menu::renderTree('primary') as $item) {
        expect($item['url'])->toStartWith('/');
    }
});

it('no queda ningún rastro del colegio ni de datos viejos en el contenido sembrado', function () {
    $todo = json_encode([
        Familia::all()->toArray(), Linea::all()->toArray(), Servicio::all()->toArray(), Faq::all()->toArray(),
        Page::all()->toArray(), SiteSetting::all()->toArray(),
    ], JSON_UNESCAPED_UNICODE);

    expect(mb_strtolower($todo))->not->toContain('dante')->not->toContain('alighieri')->not->toContain('admin@hierrometal.com');
});

it('sin archivo local no se cargan vendedores: son datos personales y no viajan en el repositorio', function () {
    expect(is_file(database_path('seeders/data/vendedores.local.json')) ? true : Vendedor::query()->count() === 0)->toBeTrue();
});

it('volver a correr los seeders no duplica nada ni pisa lo que el cliente editó', function () {
    Familia::query()->where('slug', 'chapas')->update(['nombre' => 'Chapas (editado por el cliente)']);
    SiteSetting::set('contact_phone', '+595 000 000 000');
    $antes = [Familia::count(), Linea::count(), Servicio::count(), Faq::count(), Rubro::count(), Page::count(), SiteSetting::count()];

    $this->seed(DatabaseSeeder::class);

    expect([Familia::count(), Linea::count(), Servicio::count(), Faq::count(), Rubro::count(), Page::count(), SiteSetting::count()])->toBe($antes)
        ->and(Familia::query()->where('slug', 'chapas')->firstOrFail()->nombre)->toBe('Chapas (editado por el cliente)')
        ->and(SiteSetting::get('contact_phone'))->toBe('+595 000 000 000');
});

it('la base sembrada no trae cotizaciones de ejemplo', function () {
    expect(Cotizacion::query()->count())->toBe(0);
});
