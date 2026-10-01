<?php

namespace Database\Factories;

use App\Models\Rubro;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Rubro> */
class RubroFactory extends Factory
{
    protected $model = Rubro::class;

    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(2),
            'nombre' => ucfirst(fake()->words(2, true)),
            'activo' => true,
        ];
    }
}
