<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Secciones de la portada que van debajo del hero y de los diferenciales (Productos, Servicios, Calidad y
 * Contacto): cada una se puede activar o desactivar y se ordena a mano. Las cuatro filas iniciales se crean acá,
 * así una base nueva y una existente quedan igual sin depender de ningún seeder.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secciones_inicio', function (Blueprint $table) {
            $table->id();
            $table->string('clave')->unique();
            $table->string('nombre');
            $table->boolean('activo')->default(true);
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();
        });

        foreach ([['productos', 'Productos'], ['servicios', 'Servicios'], ['calidad', 'Política de calidad'], ['contacto', 'Contacto']] as $i => [$clave, $nombre]) {
            DB::table('secciones_inicio')->insert(['clave' => $clave, 'nombre' => $nombre, 'activo' => true, 'orden' => $i + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('secciones_inicio');
    }
};
