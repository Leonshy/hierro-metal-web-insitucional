<?php

namespace Database\Factories;

use App\Models\Cotizacion;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Cotizacion> */
class CotizacionFactory extends Factory
{
    protected $model = Cotizacion::class;

    /** Datos ficticios: nada de personas reales en desarrollo ni en pruebas (regla 18). */
    public function definition(): array
    {
        return [
            'nombre' => fake()->name(),
            'empresa' => fake()->optional()->company(),
            'ci_ruc' => fake()->numerify('#.###.###'),
            'telefono' => '09'.fake()->numerify('## ### ###'),
            'email' => fake()->optional()->safeEmail(),
            'rubro' => 'Chapas de acero',
            'mensaje' => 'Necesito '.fake()->numberBetween(2, 40).' chapas de 2 mm, largo 3 m. Entrega en obra.',
            'origen' => '/productos/chapas',
            'estado' => 'nueva',
        ];
    }

    public function spam(): static
    {
        return $this->state(fn () => ['estado' => 'spam']);
    }

    public function avisada(): static
    {
        return $this->state(fn () => ['mail_enviado_at' => now()]);
    }
}
