<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Cuándo salió el correo de «pedido recibido» a quien completó el formulario (una sola vez por pedido). */
    public function up(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->timestamp('confirmacion_enviada_at')->nullable()->after('mail_enviado_at');
        });
    }

    public function down(): void
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropColumn('confirmacion_enviada_at');
        });
    }
};
