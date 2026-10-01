<?php

namespace Database\Factories;

use App\Models\Servicio;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Servicio> */
class ServicioFactory extends Factory
{
    protected $model = Servicio::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->words(2, true),
            'descripcion' => fake()->sentence(20),
            'usos' => ['Chapas', 'Barras'],
            'destacado_home' => false,
            'activo' => true,
        ];
    }
}
