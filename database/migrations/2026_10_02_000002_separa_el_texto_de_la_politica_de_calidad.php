<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * La política de calidad se editaba en un solo texto enriquecido que mezclaba la introducción con las secciones
 * de después de los compromisos. Se separa en textos independientes: la introducción y una sección por cada <h2>.
 * Es seguro repetirla: sólo actúa si la página tiene un único texto con al menos dos títulos de sección.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        $pagina = DB::table('pages')->where('slug', 'calidad')->first();

        if (! $pagina) {
            return;
        }

        $bloques = json_decode((string) $pagina->blocks, true) ?: [];
        $textos = array_values(array_filter($bloques, fn ($b) => ($b['type'] ?? null) === 'texto'));

        if (count($textos) !== 1) {
            return;
        }

        $html = (string) ($textos[0]['data']['content']['es'] ?? '');

        if (preg_match_all('/<h2\b/i', $html) < 2) {
            return;
        }

        $partes = array_values(array_filter(
            array_map('trim', preg_split('/(?=<h2\b)/i', $html, -1, PREG_SPLIT_NO_EMPTY) ?: []),
            fn ($parte) => trim(strip_tags($parte)) !== '',
        ));

        $nuevos = [];

        foreach ($bloques as $bloque) {
            if (($bloque['type'] ?? null) !== 'texto') {
                $nuevos[] = $bloque;

                continue;
            }

            foreach ($partes as $parte) {
                $nuevos[] = ['type' => 'texto', 'data' => ['content' => ['es' => $parte]]];
            }
        }

        DB::table('pages')->where('id', $pagina->id)->update(['blocks' => json_encode($nuevos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 'updated_at' => now()]);
    }

    public function down(): void
    {
        // Los textos separados no se vuelven a juntar.
    }
};
