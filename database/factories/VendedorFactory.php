<?php

namespace Database\Factories;

use App\Models\Vendedor;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Vendedor> */
class VendedorFactory extends Factory
{
    protected $model = Vendedor::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->name(),
            'telefono' => '09'.fake()->numerify('## ### ###'),
            'activo' => true,
        ];
    }
}
