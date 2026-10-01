<?php

use App\Models\Horario;
use App\Models\SiteSetting;
use App\Support\NegocioLocal;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('media');
    Storage::fake('public');
    $this->seed(DatabaseSeeder::class);
});

it('describe el negocio con dirección separada, teléfono y redes tomados del panel', function () {
    $schema = NegocioLocal::schema();

    expect($schema['@type'])->toBe('HardwareStore')
        ->and($schema['name'])->toBe('Hierro Metal S.R.L.')
        ->and($schema['telephone'])->toBe('+595981320675')
        ->and($schema['address']['streetAddress'])->toBe('Pedro Getto esq. Cadete Sisa')
        ->and($schema['address']['addressLocality'])->toBe('Fernando de la Mora')
        ->and($schema['address']['addressCountry'])->toBe('PY')
        ->and($schema['sameAs'])->toHaveCount(2)
        ->and($schema)->not->toHaveKey('geo');
});

it('publica los horarios abiertos con sus días y omite los días cerrados', function () {
    $horarios = collect(NegocioLocal::schema()['openingHoursSpecification']);

    expect($horarios)->toHaveCount(2)
        ->and($horarios[0]['dayOfWeek'])->toBe(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])
        ->and($horarios[0]['opens'])->toBe('07:00')
        ->and($horarios[0]['closes'])->toBe('17:00')
        ->and($horarios[1]['dayOfWeek'])->toBe(['Saturday'])
        ->and($horarios[1]['closes'])->toBe('12:00');
});

it('al cambiar un horario en el panel cambia el dato estructurado', function () {
    Horario::query()->where('etiqueta', 'Sábados')->update(['activo' => false]);

    expect(NegocioLocal::schema()['openingHoursSpecification'])->toHaveCount(1);
});

it('sólo incluye la geolocalización si el cliente cargó latitud y longitud', function () {
    SiteSetting::set('geo_latitud', '-25.3167');
    SiteSetting::set('geo_longitud', '-57.5833');

    expect(NegocioLocal::schema()['geo'])->toBe(['@type' => 'GeoCoordinates', 'latitude' => -25.3167, 'longitude' => -57.5833]);
});

it('omite los campos vacíos en lugar de escribir cadenas vacías', function () {
    SiteSetting::set('contact_email', '');
    SiteSetting::set('maps_url', '');

    expect(NegocioLocal::schema())->not->toHaveKeys(['email', 'hasMap']);
});

it('se incluye en todas las páginas públicas', function () {
    foreach (['/', '/productos/chapas', '/servicios', '/contacto'] as $ruta) {
        $this->get($ruta)->assertOk()->assertSee('"@type":"HardwareStore"', false)->assertSee('OpeningHoursSpecification', false);
    }
});
