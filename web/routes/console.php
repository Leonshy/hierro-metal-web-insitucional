<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Respaldos automáticos diarios (Fase 8, docs/10-seguridad.md §7). El cron de
// Plesk apunta a `php artisan schedule:run` cada minuto (CLAUDE.md §3), sin
// Supervisor — el scheduler de Laravel resuelve la periodicidad desde acá.
Schedule::command('backup:run')->daily()->at('02:00')->onOneServer();
Schedule::command('backup:clean')->daily()->at('03:00')->onOneServer();
Schedule::command('backup:monitor')->daily()->at('04:00')->onOneServer();

// Cotizaciones (CLAUDE.md regla 8). Sin Supervisor en Plesk, el cron de `schedule:run` también procesa
// la cola cada minuto, y cada cinco reintenta los avisos que no salieron.
Schedule::command('queue:work --stop-when-empty --max-time=50 --tries=3')
    ->everyMinute()
    ->withoutOverlapping()
    ->when(fn (): bool => config('queue.default') !== 'sync');
Schedule::command('cotizaciones:reintentar-avisos')->everyFiveMinutes()->withoutOverlapping();
