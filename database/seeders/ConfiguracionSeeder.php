<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Database\Seeders\Concerns\LeeContenido;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

/** Datos globales del sitio: contacto, redes, Maps, aviso de número único y texto del catálogo. */
class ConfiguracionSeeder extends Seeder
{
    use LeeContenido;

    public function run(): void
    {
        foreach ($this->contenido('ajustes') as $ajuste) {
            SiteSetting::query()->firstOrCreate(
                ['key' => $ajuste['key']],
                ['value' => $ajuste['value'], 'type' => $ajuste['type'], 'group' => $ajuste['group'], 'label' => $ajuste['label']],
            );

            Cache::forget(SiteSetting::CACHE_PREFIX.$ajuste['key']);
        }
    }
}
