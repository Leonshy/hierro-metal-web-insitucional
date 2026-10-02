<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** CI o RUC de quien pide la cotización. Nullable: los pedidos anteriores a este campo no lo tienen. */
    public function up(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->string('ci_ruc', 20)->nullable()->after('empresa')->index();
        });
    }

    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropIndex(['ci_ruc']);
            $table->dropColumn('ci_ruc');
        });
    }
};
