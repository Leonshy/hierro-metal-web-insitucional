<?php

use App\Jobs\EnviarConfirmacionCotizacion;
use App\Jobs\NotificarNuevaCotizacion;
use App\Mail\CotizacionConfirmada;
use App\Mail\CotizacionRecibida;
use App\Models\Cotizacion;
use App\Models\SiteSetting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

/** Datos válidos con una marca de tiempo de hace 10 segundos (una persona real tarda más de 3). */
function pedidoConCorreo(array $cambios = []): array
{
    return array_merge([
        'nombre' => 'Ana Prueba',
        'ci_ruc' => '1.234.567',
        'telefono' => '0981 000 000',
        'email' => 'ana@ejemplo.test',
        'mensaje' => '20 chapas galvanizadas, largo 3 m. Entrega en obra.',
        'acepto' => '1',
        'sitio_web' => '',
        '_t' => Crypt::encryptString((string) (Carbon::now()->timestamp - 10)),
    ], $cambios);
}

beforeEach(function () {
    config(['sitio.cotizaciones.email_aviso' => null]);
    SiteSetting::set('email_notificacion_cotizaciones', 'ventas@ejemplo.test');
});

it('manda el pedido recibido a quien dejó su correo, además del aviso interno', function () {
    Mail::fake();

    $this->post('/contacto', pedidoConCorreo())->assertRedirect(route('cotizaciones.gracias'));

    Mail::assertSent(CotizacionConfirmada::class, fn ($mail) => $mail->hasTo('ana@ejemplo.test') && $mail->hasReplyTo('ventas@ejemplo.test'));
    Mail::assertSent(CotizacionRecibida::class, fn ($mail) => $mail->hasTo('ventas@ejemplo.test'));
    expect(Cotizacion::query()->firstOrFail()->confirmacion_enviada_at)->not->toBeNull();
});

it('no manda el pedido recibido si no dejó correo, pero sí el aviso interno', function () {
    Mail::fake();

    $this->post('/contacto', pedidoConCorreo(['email' => '']))->assertRedirect(route('cotizaciones.gracias'));

    Mail::assertNotSent(CotizacionConfirmada::class);
    Mail::assertSent(CotizacionRecibida::class);
});

it('no manda el pedido recibido cuando es spam', function () {
    Mail::fake();

    $this->post('/contacto', pedidoConCorreo(['sitio_web' => 'http://spam.test']));

    Mail::assertNotSent(CotizacionConfirmada::class);
    Mail::assertNotSent(CotizacionRecibida::class);
});

it('encola el pedido recibido aparte del aviso interno', function () {
    Queue::fake();

    $this->post('/contacto', pedidoConCorreo());

    Queue::assertPushed(NotificarNuevaCotizacion::class);
    Queue::assertPushed(EnviarConfirmacionCotizacion::class);
});

it('no encola el pedido recibido sin correo', function () {
    Queue::fake();

    $this->post('/contacto', pedidoConCorreo(['email' => '']));

    Queue::assertPushed(NotificarNuevaCotizacion::class);
    Queue::assertNotPushed(EnviarConfirmacionCotizacion::class);
});

it('manda el pedido recibido una sola vez aunque el trabajo se repita', function () {
    Mail::fake();
    $cotizacion = Cotizacion::factory()->create(['email' => 'ana@ejemplo.test']);

    (new EnviarConfirmacionCotizacion($cotizacion->id))->handle();
    (new EnviarConfirmacionCotizacion($cotizacion->id))->handle();

    Mail::assertSent(CotizacionConfirmada::class, 1);
});

it('no repite lo que escribió la persona ni deja pasar enlaces en el saludo', function () {
    $cotizacion = Cotizacion::factory()->create([
        'nombre' => 'http://malo.example Ana',
        'mensaje' => 'TEXTO AJENO QUE NO DEBE REFLEJARSE malo.example/oferta',
    ]);

    $html = (new CotizacionConfirmada($cotizacion))->render();

    expect($html)
        ->not->toContain('TEXTO AJENO')
        ->not->toContain('malo.example')
        ->not->toContain('://malo')
        ->toContain('Recibimos tu pedido');
});

it('saluda por el primer nombre cuando es un nombre normal', function () {
    $cotizacion = Cotizacion::factory()->create(['nombre' => 'María José Gómez']);

    expect((new CotizacionConfirmada($cotizacion))->render())->toContain('Hola María,');
});

it('si el correo no sale, no marca el pedido como confirmado y el pedido sigue guardado', function () {
    config([
        'mail.default' => 'smtp',
        'mail.mailers.smtp' => ['transport' => 'smtp', 'host' => '127.0.0.1', 'port' => 1, 'timeout' => 2],
    ]);
    app('mail.manager')->purge('smtp');
    $cotizacion = Cotizacion::factory()->create(['email' => 'ana@ejemplo.test']);

    expect(fn () => (new EnviarConfirmacionCotizacion($cotizacion->id))->handle())->toThrow(Exception::class);

    expect($cotizacion->fresh()->confirmacion_enviada_at)->toBeNull();
});
