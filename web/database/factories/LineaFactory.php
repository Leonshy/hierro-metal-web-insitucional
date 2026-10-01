<?php

namespace Database\Factories;

use App\Models\Familia;
use App\Models\Linea;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Linea> */
class LineaFactory extends Factory
{
    protected $model = Linea::class;

    public function definition(): array
    {
        return [
            'familia_id' => Familia::factory(),
            'nombre' => ucfirst(fake()->words(3, true)),
            'descripcion' => fake()->sentence(14),
            'usos' => ['Estructuras', 'Herrería'],
            'medidas' => null,
            'activo' => true,
        ];
    }

    /** Una tabla de medidas con las filas de ejemplo del catálogo (chapas laminadas en frío). */
    public function conMedidas(): static
    {
        return $this->state(fn () => ['medidas' => [[
            'titulo' => 'Laminadas en frío',
            'modo' => 'tabla',
            'columnas' => ['Espesor', 'Largo (mm)', 'Ancho (mm)'],
            'filas_texto' => "N°16 – 1,50\t2000/2400/3000\t1000/1200/1500\nN°18 – 1,2\t2000/2400/3000\t1000/1200/1500",
        ]]]);
    }
}
