<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

/**
 * Quita lo que este sitio no usa y venía del proyecto base: noticias, categorías y redirecciones.
 * Es seguro en una base donde esas tablas nunca existieron o ya se borraron (todo es condicional).
 */
return new class extends Migration
{
    public function up(): void
    {
        // `posts` apunta a `categories`: primero la que depende.
        Schema::dropIfExists('posts');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('redirects');

        if (Schema::hasTable('permissions')) {
            DB::table('permissions')
                ->where('name', 'like', 'posts.%')
                ->orWhere('name', 'like', 'categories.%')
                ->orWhere('name', 'like', 'redirects.%')
                ->delete();
        }

        if (Schema::hasTable('activity_log')) {
            // El registro de auditoría guarda el nombre de la clase: sin la clase, esas filas no se podrían abrir.
            DB::table('activity_log')->whereIn('subject_type', ['App\\Models\\Post', 'App\\Models\\Category', 'App\\Models\\Redirect'])->delete();
        }

        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    public function down(): void
    {
        // No se recrean: son módulos que dejaron de existir en este sitio.
    }
};
