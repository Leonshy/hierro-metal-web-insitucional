<?php

use App\Jobs\NotificarNuevaCotizacion;
use App\Mail\CotizacionRecibida;
use App\Models\Cotizacion;
use App\Models\CotizacionAdjunto;
use App\Models\Rubro;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\Integrations\MetaConversionsApi;
use Database\Seeders\PermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

/** Datos válidos con una marca de tiempo de hace 10 segundos (una persona real tarda más de 3). */
function pedidoValido(array $cambios = []): array
{
    return array_merge([
        'nombre' => 'Persona de Prueba',
        'telefono' => '0981 000 000',
        'email' => 'prueba@ejemplo.test',
        'mensaje' => '20 chapas galvanizadas, largo 3 m. Entrega en obra.',
        'acepto' => '1',
        'sitio_web' => '',
        '_t' => Crypt::encryptString((string) (now()->timestamp - 10)),
    ], $cambios);
}

/** Un SMTP realmente caído: el servidor de correo configurado rechaza la conexión. */
function conSmtpCaido(): void
{
    config([
        'mail.default' => 'smtp',
        'mail.mailers.smtp' => ['transport' => 'smtp', 'host' => '127.0.0.1', 'port' => 1, 'timeout' => 2],
    ]);
    app('mail.manager')->purge('smtp');
}

function zipValido(string $nombre): UploadedFile
{
    $ruta = tempnam(sys_get_temp_dir(), 'xlsx');
    $zip = new ZipArchive;
    $zip->open($ruta, ZipArchive::OVERWRITE);
    $zip->addFromString('[Content_Types].xml', '<Types/>');
    $zip->close();

    return new UploadedFile($ruta, $nombre, 'application/zip', null, true);
}

beforeEach(function () {
    Storage::fake('local');
    config(['sitio.cotizaciones.email_aviso' => null]);
    SiteSetting::set('email_notificacion_cotizaciones', 'ventas@ejemplo.test');
});

it('guarda la cotización y manda al visitante a la página de gracias', function () {
    Mail::fake();

    $this->post('/contacto', pedidoValido())->assertRedirect(route('cotizaciones.gracias'));

    $cotizacion = Cotizacion::query()->firstOrFail();

    expect($cotizacion->estado)->toBe('nueva')
        ->and($cotizacion->nombre)->toBe('Persona de Prueba')
        ->and($cotizacion->uuid)->not->toBeEmpty();
});

it('encola el aviso por correo después de guardar el pedido', function () {
    Queue::fake();

    $this->post('/contacto', pedidoValido())->assertRedirect();

    Queue::assertPushed(NotificarNuevaCotizacion::class, fn ($job) => $job->cotizacionId === Cotizacion::query()->firstOrFail()->id);
    expect(Cotizacion::query()->count())->toBe(1);
});

it('avisa por correo al destinatario configurado y registra que salió', function () {
    Mail::fake();

    $this->post('/contacto', pedidoValido(['rubro' => '']));

    Mail::assertSent(CotizacionRecibida::class, fn (CotizacionRecibida $mail) => $mail->hasTo('ventas@ejemplo.test')
        && $mail->hasReplyTo('prueba@ejemplo.test')
        && str_contains($mail->envelope()->subject, 'Persona de Prueba'));
    expect(Cotizacion::query()->firstOrFail()->mail_enviado_at)->not->toBeNull();
});

it('si el SMTP está caído, la cotización queda guardada y el aviso pendiente', function () {
    conSmtpCaido();

    $this->post('/contacto', pedidoValido())->assertRedirect(route('cotizaciones.gracias'));

    $cotizacion = Cotizacion::query()->firstOrFail();

    expect($cotizacion->mail_enviado_at)->toBeNull()
        ->and($cotizacion->mail_intentos)->toBe(1)
        ->and(Cotizacion::query()->conAvisoPendiente()->count())->toBe(1);
});

it('el reintento toma el pedido pendiente cuando el correo vuelve a funcionar', function () {
    conSmtpCaido();
    $this->post('/contacto', pedidoValido());
    expect(Cotizacion::query()->firstOrFail()->mail_enviado_at)->toBeNull();

    // el SMTP vuelve
    config(['mail.default' => 'array']);
    app('mail.manager')->purge('smtp');
    Mail::fake();
    Carbon::setTestNow(now()->addMinutes(20));

    Artisan::call('cotizaciones:reintentar-avisos');

    Mail::assertSent(CotizacionRecibida::class, 1);
    expect(Cotizacion::query()->firstOrFail()->mail_enviado_at)->not->toBeNull();

    Carbon::setTestNow();
});

it('no reintenta un pedido reciente: espera el tiempo configurado', function () {
    Mail::fake();
    Cotizacion::factory()->create();

    Artisan::call('cotizaciones:reintentar-avisos');

    Mail::assertNothingSent();
});

it('sin correo de notificación configurado el pedido igual se guarda y queda pendiente', function () {
    SiteSetting::query()->where('key', 'email_notificacion_cotizaciones')->delete();
    SiteSetting::set('email_notificacion_cotizaciones', '');
    Mail::fake();

    $this->post('/contacto', pedidoValido())->assertRedirect(route('cotizaciones.gracias'));

    Mail::assertNothingSent();
    expect(Cotizacion::query()->firstOrFail()->mail_enviado_at)->toBeNull();

    SiteSetting::set('email_notificacion_cotizaciones', 'ventas@ejemplo.test');
    Carbon::setTestNow(now()->addMinutes(20));
    Artisan::call('cotizaciones:reintentar-avisos');

    Mail::assertSent(CotizacionRecibida::class, 1);

    Carbon::setTestNow();
});

it('usa la variable de entorno como respaldo del correo de notificación', function () {
    SiteSetting::set('email_notificacion_cotizaciones', '');
    config(['sitio.cotizaciones.email_aviso' => 'respaldo@ejemplo.test']);
    Mail::fake();

    $this->post('/contacto', pedidoValido());

    Mail::assertSent(CotizacionRecibida::class, fn ($mail) => $mail->hasTo('respaldo@ejemplo.test'));
});

it('alerta una sola vez si un pedido sigue sin aviso tras varios intentos', function () {
    SiteSetting::set('email_notificacion_cotizaciones', '');
    Log::spy();
    $cotizacion = Cotizacion::factory()->create(['mail_intentos' => 3, 'created_at' => now()->subHour()]);

    Artisan::call('cotizaciones:reintentar-avisos');
    Artisan::call('cotizaciones:reintentar-avisos');

    Log::shouldHaveReceived('critical')->once();
    expect($cotizacion->refresh()->alertada_at)->not->toBeNull();
});

it('un intento con el campo señuelo lleno se guarda como spam, sin aviso y con la misma respuesta', function () {
    Mail::fake();
    Queue::fake();

    $this->post('/contacto', pedidoValido(['sitio_web' => 'http://spam.test']))->assertRedirect(route('cotizaciones.gracias'));

    expect(Cotizacion::query()->firstOrFail()->estado)->toBe('spam');
    Queue::assertNothingPushed();
    Mail::assertNothingSent();
});

it('un envío demasiado rápido, sin marca o con una marca falsificada se guarda como spam', function () {
    Queue::fake();

    $this->post('/contacto', pedidoValido(['_t' => Crypt::encryptString((string) now()->timestamp)]))->assertRedirect();
    $this->post('/contacto', pedidoValido(['_t' => '']))->assertRedirect();
    $this->post('/contacto', pedidoValido(['_t' => 'esto-no-es-una-marca-firmada']))->assertRedirect();

    expect(Cotizacion::query()->where('estado', 'spam')->count())->toBe(3);
    Queue::assertNothingPushed();
});

it('el spam no guarda archivos', function () {
    $this->post('/contacto', pedidoValido(['sitio_web' => 'x', 'archivos' => [UploadedFile::fake()->createWithContent('plano.pdf', '%PDF-1.4 prueba')]]));

    expect(CotizacionAdjunto::query()->count())->toBe(0);
    Storage::disk('local')->assertDirectoryEmpty('cotizaciones');
});

it('exige los datos obligatorios con mensajes en la voz del sitio', function () {
    $this->post('/contacto', ['acepto' => '0'])
        ->assertSessionHasErrors(['nombre', 'telefono', 'mensaje', 'acepto']);

    $errores = session('errors');

    expect($errores->first('nombre'))->toBe('Decinos tu nombre y apellido.')
        ->and($errores->first('telefono'))->toBe('Dejanos un teléfono o WhatsApp para responderte.')
        ->and($errores->first('mensaje'))->toBe('Contanos qué materiales necesitás.')
        ->and($errores->first('acepto'))->toBe('Para enviar el pedido tenés que aceptar el uso de tus datos.');
    expect(Cotizacion::query()->count())->toBe(0);
});

it('valida el formato del teléfono y del correo', function () {
    $this->post('/contacto', pedidoValido(['telefono' => 'abc', 'email' => 'no-es-correo']))
        ->assertSessionHasErrors(['telefono', 'email']);

    expect(session('errors')->first('telefono'))->toBe('Revisá el teléfono: parece incompleto.')
        ->and(session('errors')->first('email'))->toBe('Revisá el correo: parece tener un error.');
});

it('sólo acepta un rubro que exista y esté visible', function () {
    Rubro::factory()->create(['slug' => 'chapas', 'nombre' => 'Chapas de acero']);
    Rubro::factory()->create(['slug' => 'oculto', 'activo' => false]);
    Mail::fake();

    $this->post('/contacto', pedidoValido(['rubro' => 'oculto']))->assertSessionHasErrors('rubro');
    $this->post('/contacto', pedidoValido(['rubro' => 'chapas']))->assertSessionDoesntHaveErrors();

    expect(Cotizacion::query()->firstOrFail()->rubro)->toBe('Chapas de acero');
});

it('la página del formulario muestra los rubros y preselecciona el que viene en la dirección', function () {
    Rubro::factory()->create(['slug' => 'chapas', 'nombre' => 'Chapas de acero']);
    Rubro::factory()->create(['slug' => 'tubos', 'nombre' => 'Tubos y caños']);

    $this->get('/contacto?rubro=tubos')
        ->assertOk()
        ->assertSee('Chapas de acero')
        ->assertSee('<option value="tubos" selected>', false);
});

it('guarda sólo la ruta interna de origen y las campañas conocidas', function () {
    Mail::fake();

    $this->post('/contacto', pedidoValido(['origen' => 'https://sitio-externo.test/x', 'utm' => ['source' => 'instagram', 'campaign' => 'oct', 'otro' => 'ignorado']]));

    $cotizacion = Cotizacion::query()->firstOrFail();

    expect($cotizacion->origen)->toBeNull()
        ->and($cotizacion->utm)->toBe(['source' => 'instagram', 'campaign' => 'oct']);
});

// ------------------------------------------------------------------ adjuntos

it('guarda planos y despieces en disco privado con nombre propio', function () {
    Mail::fake();

    $this->post('/contacto', pedidoValido(['archivos' => [
        UploadedFile::fake()->createWithContent('plano galpón.pdf', "%PDF-1.4\nprueba"),
        UploadedFile::fake()->image('foto.png'),
        UploadedFile::fake()->createWithContent('corte.dwg', "AC1018\0\0\0\0\0\0\0datos"),
    ]]))->assertSessionDoesntHaveErrors();

    $cotizacion = Cotizacion::query()->with('adjuntos')->firstOrFail();

    expect($cotizacion->adjuntos)->toHaveCount(3);

    foreach ($cotizacion->adjuntos as $adjunto) {
        expect($adjunto->ruta)->toStartWith("cotizaciones/{$cotizacion->uuid}/")
            ->and($adjunto->ruta)->not->toContain('galpón')
            ->and($adjunto->disco)->toBe('local');
        Storage::disk('local')->assertExists($adjunto->ruta);
    }
});

it('acepta DXF, XLSX y WEBP por su contenido real', function () {
    Mail::fake();

    $this->post('/contacto', pedidoValido(['archivos' => [
        UploadedFile::fake()->createWithContent('pieza.dxf', "  0\nSECTION\n  2\nHEADER\n"),
        zipValido('despiece.xlsx'),
    ]]))->assertSessionDoesntHaveErrors();

    expect(CotizacionAdjunto::query()->count())->toBe(2);
});

it('rechaza una extensión fuera de la lista blanca', function () {
    $this->post('/contacto', pedidoValido(['archivos' => [UploadedFile::fake()->createWithContent('virus.exe', 'MZ')]]))
        ->assertSessionHasErrors('archivos.0');

    expect(session('errors')->first('archivos.0'))->toBe('Ese tipo de archivo no se puede adjuntar. Usá PDF, JPG, PNG, WEBP, DWG, DXF o XLSX.')
        ->and(Cotizacion::query()->count())->toBe(0);
});

it('rechaza un archivo cuyo contenido no corresponde a su extensión', function () {
    $this->post('/contacto', pedidoValido(['archivos' => [UploadedFile::fake()->createWithContent('plano.pdf', '<?php echo "x";')]]))
        ->assertSessionHasErrors('archivos.0');

    $this->post('/contacto', pedidoValido(['archivos' => [UploadedFile::fake()->createWithContent('plano.dwg', 'texto cualquiera')]]))
        ->assertSessionHasErrors('archivos.0');

    $this->post('/contacto', pedidoValido(['archivos' => [UploadedFile::fake()->createWithContent('foto.png', 'no soy una imagen')]]))
        ->assertSessionHasErrors('archivos.0');
});

it('rechaza un archivo de más de 10 MB con el mensaje de la voz del sitio', function () {
    $this->post('/contacto', pedidoValido(['archivos' => [UploadedFile::fake()->create('plano.pdf', 10241, 'application/pdf')]]))
        ->assertSessionHasErrors('archivos.0');

    expect(session('errors')->first('archivos.0'))->toBe('El archivo pesa más de 10 MB. Probá comprimirlo o mandanos el plano por WhatsApp.');
});

it('admite hasta 3 archivos', function () {
    $archivos = fn (int $n) => collect(range(1, $n))->map(fn ($i) => UploadedFile::fake()->createWithContent("p{$i}.pdf", '%PDF-1.4'))->all();

    $this->post('/contacto', pedidoValido(['archivos' => $archivos(4)]))->assertSessionHasErrors('archivos');

    expect(session('errors')->first('archivos'))->toBe('Podés adjuntar hasta 3 archivos.');
});

it('un adjunto que no se puede guardar no cuesta el pedido', function () {
    Mail::fake();
    Storage::shouldReceive('disk')->andThrow(new RuntimeException('disco lleno'));

    $this->post('/contacto', pedidoValido(['archivos' => [UploadedFile::fake()->createWithContent('plano.pdf', '%PDF-1.4')]]))
        ->assertRedirect(route('cotizaciones.gracias'));

    expect(Cotizacion::query()->count())->toBe(1)
        ->and(Cotizacion::query()->firstOrFail()->notas_internas)->toContain('No se pudo guardar el adjunto');
});

it('los adjuntos no se pueden pedir por una dirección pública', function () {
    Mail::fake();
    $this->post('/contacto', pedidoValido(['archivos' => [UploadedFile::fake()->createWithContent('plano.pdf', '%PDF-1.4')]]));
    $adjunto = CotizacionAdjunto::query()->firstOrFail();

    // El disco privado sólo se sirve con una dirección firmada y temporal: sin firma, nunca 200.
    expect($this->get('/storage/'.$adjunto->ruta)->getStatusCode())->toBeIn([403, 404])
        ->and($this->get('/'.$adjunto->ruta)->getStatusCode())->toBeIn([403, 404]);
    expect(Storage::disk('local')->path($adjunto->ruta))->not->toContain('/public/');
});

it('sin sesión del panel el adjunto no se descarga ni se confirma que existe', function () {
    $adjunto = adjuntoGuardado();

    $this->get(route('cotizaciones.adjunto', $adjunto))->assertNotFound();
});

it('con permiso se descarga siempre como archivo, nunca en línea', function () {
    $adjunto = adjuntoGuardado();
    $this->seed(PermissionSeeder::class);
    $ventas = User::factory()->create(['is_active' => true]);
    $ventas->assignRole('ventas');

    $respuesta = $this->actingAs($ventas)->get(route('cotizaciones.adjunto', $adjunto))->assertOk();

    expect($respuesta->headers->get('Content-Type'))->toBe('application/octet-stream')
        ->and($respuesta->headers->get('X-Content-Type-Options'))->toBe('nosniff')
        ->and($respuesta->headers->get('Content-Disposition'))->toContain('attachment');
});

it('un usuario sin permiso sobre cotizaciones recibe 403 al pedir un adjunto', function () {
    $adjunto = adjuntoGuardado();
    $this->seed(PermissionSeeder::class);
    $editor = User::factory()->create(['is_active' => true]);
    $editor->assignRole('editor');

    $this->actingAs($editor)->get(route('cotizaciones.adjunto', $adjunto))->assertForbidden();
});

function adjuntoGuardado(): CotizacionAdjunto
{
    Storage::fake('local');
    $cotizacion = Cotizacion::factory()->create();
    Storage::disk('local')->put("cotizaciones/{$cotizacion->uuid}/a.pdf", '%PDF-1.4 prueba');

    return CotizacionAdjunto::query()->create([
        'cotizacion_id' => $cotizacion->id, 'disco' => 'local', 'ruta' => "cotizaciones/{$cotizacion->uuid}/a.pdf",
        'nombre_original' => 'plano.pdf', 'mime' => 'application/pdf', 'tamano' => 15,
    ]);
}

// ------------------------------------------------------------------ límites e integraciones

it('limita los envíos por IP: el sexto en una hora se rechaza', function () {
    Mail::fake();

    foreach (range(1, 5) as $i) {
        $this->post('/contacto', pedidoValido())->assertRedirect();
    }

    $this->post('/contacto', pedidoValido())->assertStatus(429);
});

it('le avisa a Meta sólo con consentimiento de publicidad y sin datos personales por defecto', function () {
    Mail::fake();
    $meta = $this->mock(MetaConversionsApi::class);
    $meta->shouldReceive('send')->once()->with('Lead', [], Mockery::any())->andReturn('evento-1');

    $this->withUnencryptedCookie('sitio_consent_marketing', '1')
        ->post('/contacto', pedidoValido())
        ->assertSessionHas('meta_event_id', 'evento-1');
});

it('no le avisa a Meta si la persona no aceptó las cookies de publicidad', function () {
    Mail::fake();
    $meta = $this->mock(MetaConversionsApi::class);
    $meta->shouldNotReceive('send');

    $this->post('/contacto', pedidoValido())->assertRedirect();
});

it('manda el teléfono y el correo a Meta sólo si se activa expresamente', function () {
    Mail::fake();
    config(['sitio.cotizaciones.meta_enviar_datos_personales' => true]);
    $meta = $this->mock(MetaConversionsApi::class);
    $meta->shouldReceive('send')->once()->with('Lead', ['email' => 'prueba@ejemplo.test', 'phone' => '0981 000 000'], Mockery::any())->andReturn('e');

    $this->withUnencryptedCookie('sitio_consent_marketing', '1')->post('/contacto', pedidoValido());
});

it('la página de gracias no se indexa', function () {
    $this->get('/contacto/gracias')->assertOk()->assertSee('Recibimos tu pedido')->assertSee('noindex', false);
});

// ------------------------------------------------------------------ modelo

it('convierte el teléfono paraguayo al formato internacional de WhatsApp', function () {
    expect(Cotizacion::factory()->make(['telefono' => '0981 320 675'])->telefonoInternacional())->toBe('595981320675')
        ->and(Cotizacion::factory()->make(['telefono' => '+595 981 320 675'])->telefonoInternacional())->toBe('595981320675')
        ->and(Cotizacion::factory()->make(['telefono' => '00595981320675'])->telefonoInternacional())->toBe('595981320675');
});

it('arma el enlace de «Responder por WhatsApp» con el primer nombre', function () {
    $url = Cotizacion::factory()->make(['nombre' => 'Marcos Benítez', 'telefono' => '0981 320 675'])->whatsappUrl();

    expect($url)->toStartWith('https://wa.me/595981320675?text=')
        ->and(urldecode($url))->toContain('Hola Marcos, te escribimos de Hierro Metal');
});
