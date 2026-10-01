<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('familias', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 40)->unique();
            $table->string('nombre', 80);
            $table->string('resumen_home', 200);
            $table->text('bajada');
            $table->string('ilustracion', 20)->default('ninguna');
            $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->string('mensaje_whatsapp', 200)->nullable();
            $table->string('seo_titulo', 70)->nullable();
            $table->string('seo_descripcion', 200)->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['activo', 'orden']);
        });

        Schema::create('lineas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('familia_id')->constrained('familias')->cascadeOnDelete();
            $table->string('nombre', 200);
            $table->text('descripcion');
            $table->json('usos')->nullable();
            $table->json('medidas')->nullable();
            $table->string('nota_medidas', 300)->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['familia_id', 'activo', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lineas');
        Schema::dropIfExists('familias');
    }
};
