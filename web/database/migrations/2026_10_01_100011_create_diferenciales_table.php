<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diferenciales', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 60);
            $table->string('texto', 120);
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['activo', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diferenciales');
    }
};
