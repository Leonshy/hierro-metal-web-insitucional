<?php

namespace Database\Factories;

use App\Models\Diferencial;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Diferencial> */
class DiferencialFactory extends Factory
{
    protected $model = Diferencial::class;

    public function definition(): array
    {
        return [
            'titulo' => fake()->words(2, true),
            'texto' => fake()->sentence(4),
            'activo' => true,
        ];
    }
}
