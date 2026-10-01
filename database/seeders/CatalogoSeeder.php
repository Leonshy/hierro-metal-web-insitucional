<?php

namespace Database\Seeders;

use App\Models\Familia;
use App\Models\Linea;
use Database\Seeders\Concerns\LeeContenido;
use Illuminate\Database\Seeder;

/** Las 5 familias del catálogo con sus 16 líneas y las tablas de medidas del catálogo 2026. */
class CatalogoSeeder extends Seeder
{
    use LeeContenido;

    public function run(): void
    {
        foreach ($this->contenido('familias') as $datos) {
            $familia = Familia::query()->firstOrCreate(['slug' => $datos['slug']], [
                'nombre' => $datos['nombre'],
                'resumen_home' => $datos['resumen_home'],
                'bajada' => $datos['bajada'],
                'ilustracion' => $datos['ilustracion'],
                'seo_titulo' => $datos['seo_titulo'],
                'seo_descripcion' => $datos['seo_descripcion'],
                'activo' => true,
            ]);

            foreach ($datos['lineas'] as $linea) {
                Linea::query()->firstOrCreate(['familia_id' => $familia->id, 'nombre' => $linea['nombre']], [
                    'descripcion' => $linea['descripcion'],
                    'usos' => $linea['usos'],
                    'medidas' => $linea['medidas'] === [] ? null : $linea['medidas'],
                    'nota_medidas' => $linea['nota_medidas'],
                    'activo' => true,
                ]);
            }
        }
    }
}
