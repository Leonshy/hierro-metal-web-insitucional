<?php

namespace Database\Factories;

use App\Models\Paso;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Paso> */
class PasoFactory extends Factory
{
    protected $model = Paso::class;

    public function definition(): array
    {
        return [
            'titulo' => fake()->words(3, true),
            'texto' => fake()->sentence(14),
            'activo' => true,
        ];
    }
}
