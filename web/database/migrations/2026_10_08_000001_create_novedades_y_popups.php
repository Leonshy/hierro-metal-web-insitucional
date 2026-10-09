<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Novedades (blog) y pop-ups. Además de las tablas, deja listo todo lo que un seeder no puede entregar en un sitio ya
 * publicado: la página estructural «novedades», su entrada en la portada y en los menús, y los permisos de los roles.
 * Cada paso es idempotente: es seguro en una base nueva (donde el seeder carga lo mismo) y en una existente.
 */
return new class extends Migration
{
    private const ACCIONES = ['view', 'create', 'update', 'delete', 'publish'];

    public function up(): void
    {
        Schema::create('novedades', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 160);
            $table->string('slug', 120)->unique();
            $table->string('resumen', 300);
            $table->longText('contenido');
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->dateTime('publicada_en')->nullable();
            $table->boolean('activo')->default(true);
            $table->string('seo_titulo', 70)->nullable();
            $table->string('seo_descripcion', 200)->nullable();
            $table->timestamps();

            $table->index(['activo', 'publicada_en']);
        });

        Schema::create('popups', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 120);
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('texto_alternativo', 200)->nullable();
            $table->string('enlace', 500)->nullable();
            $table->boolean('nueva_pestana')->default(false);
            $table->string('donde', 10)->default('inicio');
            $table->string('frecuencia', 10)->default('sesion');
            $table->dateTime('desde')->nullable();
            $table->dateTime('hasta')->nullable();
            $table->boolean('activo')->default(true);
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();

            $table->index(['activo', 'orden']);
        });

        $this->paginaDeLaSeccion();
        $this->seccionDeLaPortada();
        $this->enlaceEnLosMenus();
        $this->permisos();
    }

    public function down(): void
    {
        Schema::dropIfExists('popups');
        Schema::dropIfExists('novedades');
    }

    private function paginaDeLaSeccion(): void
    {
        if (! Schema::hasTable('pages') || DB::table('pages')->where('slug', 'novedades')->exists()) {
            return;
        }

        DB::table('pages')->insert([
            'slug' => 'novedades',
            'title' => json_encode(['es' => 'Novedades'], JSON_UNESCAPED_UNICODE),
            'site_section' => 'general',
            'blocks' => json_encode([['type' => 'hero', 'data' => [
                'title' => ['es' => 'Novedades'],
                'subtitle' => ['es' => 'Lo último de Hierro Metal: llegadas de material, nuevos servicios y avisos para nuestros clientes.'],
            ]]], JSON_UNESCAPED_UNICODE),
            'status' => 'published',
            'published_at' => now(),
            'is_indexable' => true,
            'seo_title' => json_encode(['es' => 'Novedades · Hierro Metal S.R.L.'], JSON_UNESCAPED_UNICODE),
            'seo_description' => json_encode(['es' => 'Novedades de Hierro Metal S.R.L.: llegadas de material, servicios nuevos y avisos para clientes de chapas, perfiles y tubos de acero.'], JSON_UNESCAPED_UNICODE),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /** Va entre «Política de calidad» y «Contacto». */
    private function seccionDeLaPortada(): void
    {
        if (! Schema::hasTable('secciones_inicio') || DB::table('secciones_inicio')->where('clave', 'novedades')->exists()) {
            return;
        }

        $contacto = DB::table('secciones_inicio')->where('clave', 'contacto')->value('orden');
        $orden = $contacto ?? ((int) DB::table('secciones_inicio')->max('orden') + 1);

        DB::table('secciones_inicio')->where('orden', '>=', $orden)->increment('orden');
        DB::table('secciones_inicio')->insert([
            'clave' => 'novedades', 'nombre' => 'Novedades', 'activo' => true, 'orden' => $orden,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    /** «Novedades» va después de Calidad en el menú principal y en el del pie (si esos menús ya existen). */
    private function enlaceEnLosMenus(): void
    {
        if (! Schema::hasTable('menus') || ! Schema::hasTable('menu_items')) {
            return;
        }

        foreach (['primary' => 'Novedades', 'footer_secondary' => 'Novedades'] as $clave => $etiqueta) {
            $menu = DB::table('menus')->where('key', $clave)->first();

            if (! $menu || DB::table('menu_items')->where('menu_id', $menu->id)->where('url', '/novedades')->exists()) {
                continue;
            }

            $calidad = DB::table('menu_items')->where('menu_id', $menu->id)->whereNull('parent_id')->where('url', '/calidad')->value('sort_order');
            $lugar = $calidad !== null ? $calidad + 1 : ((int) DB::table('menu_items')->where('menu_id', $menu->id)->max('sort_order') + 1);

            DB::table('menu_items')->where('menu_id', $menu->id)->where('sort_order', '>=', $lugar)->increment('sort_order');
            DB::table('menu_items')->insert([
                'menu_id' => $menu->id, 'label' => json_encode(['es' => $etiqueta], JSON_UNESCAPED_UNICODE), 'url' => '/novedades',
                'sort_order' => $lugar, 'open_in_new_tab' => false, 'is_active' => true,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    /** El administrador lo hace todo; el editor carga novedades y pop-ups como el resto del contenido. */
    private function permisos(): void
    {
        if (! Schema::hasTable('permissions')) {
            return;
        }

        $nombres = [];

        foreach (['novedades', 'popups'] as $modulo) {
            foreach (self::ACCIONES as $accion) {
                $nombres[] = Permission::findOrCreate("{$modulo}.{$accion}")->name;
            }
        }

        $editables = array_values(array_filter($nombres, fn (string $n): bool => ! str_ends_with($n, '.publish')));

        Role::query()->where('name', 'administrador')->first()?->givePermissionTo($nombres);
        Role::query()->where('name', 'editor')->first()?->givePermissionTo($editables);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
