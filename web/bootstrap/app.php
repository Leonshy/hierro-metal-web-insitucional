<?php

use App\Http\Middleware\PublicMaintenanceMode;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Spatie\Honeypot\ProtectAgainstSpam;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global: corre en toda respuesta HTTP, panel incluido (Fase 8,
        // docs/10-seguridad.md §1) — no solo en el grupo `web`.
        $middleware->append(SecurityHeaders::class);

        $middleware->web(prepend: [
            PublicMaintenanceMode::class,
        ]);

        // Las cookies de consentimiento las escribe el JavaScript del banner, en texto plano
        // (`resources/js/consent.js`). Sin esta excepción Laravel las descarta por no estar
        // cifradas y el servidor nunca vería el consentimiento (Meta no recibiría el evento
        // aunque la persona lo hubiera aceptado). Son un 0 o un 1: nada sensible.
        $middleware->encryptCookies(except: ['sitio_consent', 'sitio_consent_marketing', 'sitio_consent_analytics']);

        $middleware->alias([
            'honeypot' => ProtectAgainstSpam::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
