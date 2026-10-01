<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nombre', 120);
            $table->string('empresa', 120)->nullable();
            $table->string('telefono', 40);
            $table->string('email', 150)->nullable();
            $table->string('rubro', 100)->nullable();
            $table->text('mensaje');
            $table->string('origen', 300)->nullable();
            $table->json('utm')->nullable();
            // nueva | en_curso | cotizada | ganada | perdida | spam
            $table->string('estado', 20)->default('nueva');
            $table->foreignId('asignado_a')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notas_internas')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 300)->nullable();
            $table->timestamp('mail_enviado_at')->nullable();
            $table->unsignedTinyInteger('mail_intentos')->default(0);
            $table->timestamp('alertada_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['estado', 'created_at']);
            $table->index('mail_enviado_at');
        });

        Schema::create('cotizacion_adjuntos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotizacion_id')->constrained('cotizaciones')->cascadeOnDelete();
            $table->string('disco', 20)->default('local');
            $table->string('ruta', 300);
            $table->string('nombre_original', 255);
            $table->string('mime', 100);
            $table->unsignedInteger('tamano');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizacion_adjuntos');
        Schema::dropIfExists('cotizaciones');
    }
};
