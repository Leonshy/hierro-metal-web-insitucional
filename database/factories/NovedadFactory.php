<?php

namespace Database\Factories;

use App\Models\Novedad;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Novedad> */
class NovedadFactory extends Factory
{
    protected $model = Novedad::class;

    public function definition(): array
    {
        $titulo = rtrim(fake()->sentence(5), '.');

        return [
            'titulo' => $titulo,
            'slug' => Str::slug($titulo).'-'.fake()->unique()->numberBetween(1, 99999),
            'resumen' => fake()->sentence(14),
            'contenido' => '<p>'.fake()->sentence(30).'</p>',
            'publicada_en' => now()->subDay(),
            'activo' => true,
        ];
    }

    /** Programada para más adelante: todavía no se ve. */
    public function programada(): static
    {
        return $this->state(fn () => ['publicada_en' => now()->addWeek()]);
    }
}
