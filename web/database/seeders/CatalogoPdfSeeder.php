<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Support\Catalogo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Deja cargado el catálogo 2026 del cliente (`data/catalogo-2026.pdf`) para que los botones «Descargar catálogo»
 * del sitio funcionen desde el primer día. No pisa nada: si el cliente ya subió uno desde el panel
 * (Configuraciones → Catálogo PDF), se respeta.
 */
class CatalogoPdfSeeder extends Seeder
{
    public function run(): void
    {
        $origen = database_path('seeders/data/catalogo-2026.pdf');

        if (Catalogo::disponible() || ! is_file($origen)) {
            return;
        }

        $ruta = 'catalogo/catalogo-2026.pdf';

        Storage::disk(Catalogo::DISCO)->put($ruta, file_get_contents($origen));
        SiteSetting::set('catalogo_path', $ruta, 'text', 'catalogo');
    }
}
