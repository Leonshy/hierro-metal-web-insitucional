<?php

namespace Database\Seeders;

use App\Models\Compromiso;
use App\Models\Diferencial;
use App\Models\Familia;
use App\Models\Faq;
use App\Models\Horario;
use App\Models\Paso;
use App\Models\Rubro;
use App\Models\Servicio;
use Database\Seeders\Concerns\LeeContenido;
use Illuminate\Database\Seeder;

/** Servicios, pasos, compromisos, diferenciales, preguntas frecuentes, horarios y rubros del formulario. */
class ContenidoSeeder extends Seeder
{
    use LeeContenido;

    public function run(): void
    {
        foreach ($this->contenido('servicios') as $s) {
            Servicio::query()->firstOrCreate(['nombre' => $s['nombre']], [
                'descripcion' => $s['descripcion'], 'usos' => $s['usos'], 'destacado_home' => $s['destacado_home'],
                'titulo_home' => $s['titulo_home'], 'resumen_home' => $s['resumen_home'], 'activo' => true,
            ]);
        }

        foreach ($this->contenido('pasos') as $p) {
            Paso::query()->firstOrCreate(['titulo' => $p['titulo']], ['texto' => $p['texto'], 'activo' => true]);
        }

        foreach ($this->contenido('compromisos') as $c) {
            Compromiso::query()->firstOrCreate(['titulo' => $c['titulo']], ['texto' => $c['texto'], 'activo' => true]);
        }

        foreach ($this->contenido('diferenciales') as $d) {
            Diferencial::query()->firstOrCreate(['titulo' => $d['titulo']], ['texto' => $d['texto'], 'activo' => true]);
        }

        foreach ($this->contenido('faqs') as $f) {
            Faq::query()->firstOrCreate(['pregunta' => $f['pregunta']], ['respuesta' => $f['respuesta'], 'activo' => true]);
        }

        foreach ($this->contenido('horarios') as $h) {
            Horario::query()->firstOrCreate(['etiqueta' => $h['etiqueta']], [
                'dias' => $h['dias'], 'abre' => $h['abre'], 'cierra' => $h['cierra'], 'cerrado' => $h['cerrado'], 'activo' => true,
            ]);
        }

        foreach ($this->contenido('rubros') as $r) {
            Rubro::query()->firstOrCreate(['slug' => $r['slug']], [
                'nombre' => $r['nombre'],
                'familia_id' => isset($r['familia']) ? Familia::query()->where('slug', $r['familia'])->value('id') : null,
                'servicio_id' => isset($r['servicio']) ? Servicio::query()->where('nombre', $r['servicio'])->value('id') : null,
                'activo' => true,
            ]);
        }
    }
}
