<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

/**
 * Quita el módulo Vendedores, que este sitio no usa: su tabla, sus permisos y sus registros de auditoría.
 * Es seguro en una base donde la tabla nunca existió o ya se borró.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('vendedores');

        if (Schema::hasTable('permissions')) {
            DB::table('permissions')->where('name', 'like', 'vendedores.%')->delete();
        }

        if (Schema::hasTable('activity_log')) {
            DB::table('activity_log')->where('subject_type', 'App\\Models\\Vendedor')->delete();
        }

        if (class_exists(PermissionRegistrar::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }

    public function down(): void
    {
        // No se recrea: es un módulo que dejó de existir en este sitio.
    }
};
