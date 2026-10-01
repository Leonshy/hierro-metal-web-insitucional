<?php

namespace Database\Seeders;

use App\Models\Familia;
use App\Models\Page;
use App\Services\Media\MediaUploadService;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

/**
 * Fotos del catálogo PDF del cliente (carpeta `data/fotos`): una por familia, en la biblioteca de
 * medios, y la de portada como foto del hero de la página «inicio». No pisa lo que el cliente ya
 * haya cambiado desde el panel: sólo completa familias y portada que no tienen foto.
 */
class FotosSeeder extends Seeder
{
    private const ALT_PORTADA = 'Secciones de acero plegado con reflejos, en blanco y negro';

    private const FAMILIAS = [
        'chapas' => 'Pila de chapas de acero laminadas',
        'perfiles' => 'Perfil UPN de acero',
        'tubos' => 'Caños redondos y tubos cuadrados y rectangulares apilados en el depósito',
        'varillas' => 'Varillas lisas de acero',
        'accesorios' => 'Caños anti-incendio de acero pintados de rojo',
    ];

    public function run(MediaUploadService $medios): void
    {
        foreach (self::FAMILIAS as $slug => $alt) {
            $familia = Familia::query()->where('slug', $slug)->first();
            $archivo = $this->archivo($slug);

            if (! $familia || $familia->media_id || ! $archivo) {
                continue;
            }

            $media = $medios->upload($archivo, 'catalogo', $alt);
            $familia->update(['media_id' => $media->id]);
        }

        $this->portada($medios);
    }

    private function portada(MediaUploadService $medios): void
    {
        $pagina = Page::query()->where('slug', 'inicio')->first();
        $archivo = $this->archivo('portada');

        if (! $pagina || ! $archivo) {
            return;
        }

        $bloques = $pagina->blocks;

        foreach ($bloques as $i => $bloque) {
            if (($bloque['type'] ?? null) !== 'hero' || ! empty($bloque['data']['media_id'])) {
                continue;
            }

            $bloques[$i]['data']['media_id'] = $medios->upload($archivo, 'catalogo', self::ALT_PORTADA)->id;
        }

        $pagina->update(['blocks' => $bloques]);
    }

    private function ruta(string $nombre): string
    {
        return database_path("seeders/data/fotos/{$nombre}.jpg");
    }

    private function archivo(string $nombre): ?UploadedFile
    {
        $ruta = $this->ruta($nombre);

        // `test: true`: es un archivo local de confianza, no una subida HTTP.
        return is_file($ruta) ? new UploadedFile($ruta, "{$nombre}.jpg", 'image/jpeg', null, true) : null;
    }
}
