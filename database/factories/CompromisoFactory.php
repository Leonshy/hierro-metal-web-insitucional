<?php

namespace Database\Factories;

use App\Models\Compromiso;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Compromiso> */
class CompromisoFactory extends Factory
{
    protected $model = Compromiso::class;

    public function definition(): array
    {
        return [
            'titulo' => fake()->words(3, true),
            'texto' => fake()->sentence(18),
            'activo' => true,
        ];
    }
}
