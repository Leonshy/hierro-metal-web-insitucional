<?php

namespace Database\Factories;

use App\Models\Familia;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Familia> */
class FamiliaFactory extends Factory
{
    protected $model = Familia::class;

    public function definition(): array
    {
        $nombre = fake()->unique()->words(2, true);

        return [
            'slug' => Str::slug($nombre).'-'.fake()->unique()->numerify('###'),
            'nombre' => ucfirst($nombre),
            'resumen_home' => fake()->sentence(14),
            'bajada' => fake()->sentence(24),
            'ilustracion' => 'chapas',
            'activo' => true,
        ];
    }
}
