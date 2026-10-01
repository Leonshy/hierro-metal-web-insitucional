<?php

namespace Database\Seeders;

use App\Models\Vendedor;
use Illuminate\Database\Seeder;

/**
 * Los vendedores son datos personales de empleados: no viajan en el repositorio (regla 18).
 * Si existe `database/seeders/data/vendedores.local.json` (ignorado por git) se cargan **inactivos**: no se
 * publican hasta que cada persona dé su consentimiento y alguien los active desde el panel.
 * Formato: [{"nombre": "…", "telefono": "…"}].
 */
class VendedoresSeeder extends Seeder
{
    public function run(): void
    {
        $archivo = database_path('seeders/data/vendedores.local.json');

        if (! is_file($archivo)) {
            return;
        }

        foreach (json_decode((string) file_get_contents($archivo), true, flags: JSON_THROW_ON_ERROR) as $vendedor) {
            Vendedor::query()->firstOrCreate(['nombre' => $vendedor['nombre']], ['telefono' => $vendedor['telefono'], 'activo' => false]);
        }
    }
}
