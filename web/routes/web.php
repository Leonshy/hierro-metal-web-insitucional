<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/robots.txt', [RobotsController::class, 'index'])->name('robots.index');

Route::get('/contacto', [ContactController::class, 'show'])->name('contact.show');

// Formularios públicos — honeypot + rate limiting (ausentes en IPG, docs/01 §A.3).
// 5 envíos por hora por IP (docs/10-seguridad.md §6) — antes era `throttle:5,1`
// (5 por MINUTO), un límite 60x más laxo que el documentado y suficiente para
// que un bot simple agotara el buzón de contacto. Corregido en Fase 8.
//
// Hallazgo real de Fase 9: `ThrottleRequests::resolveRequestSignature()` arma
// la clave de caché solo con `dominio|IP` cuando no se pasa un prefijo — es
// decir que, SIN el tercer parámetro `,forms`, este límite y el del buscador
// de abajo compartían exactamente el mismo contador por IP a pesar de tener
// topes distintos (5/hora vs. 30/min). En la práctica, un visitante que
// buscaba varias veces podía agotar el contador y quedar bloqueado para
// enviar el formulario de contacto sin haberlo tocado nunca, y viceversa.
// Reproducido con Playwright (dos envíos de formulario devolvían 429) y
// corregido dándole un prefijo propio a cada grupo.
Route::middleware(['honeypot', 'throttle:5,60,forms'])->group(function () {
    Route::post('/contacto', [FormSubmissionController::class, 'contact'])->name('forms.contact');
});

// Catch-all de páginas públicas (institucionales / landings de sección) — el
// middleware de redirecciones corre antes (bootstrap/app.php). Va al final
// para no interceptar las rutas específicas de arriba.
Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '[A-Za-z0-9\-\/]+')
    ->name('pages.show');
