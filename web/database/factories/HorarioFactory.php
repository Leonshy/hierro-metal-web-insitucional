<?php

namespace Database\Factories;

use App\Models\Horario;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Horario> */
class HorarioFactory extends Factory
{
    protected $model = Horario::class;

    public function definition(): array
    {
        return [
            'etiqueta' => 'Lunes a viernes',
            'dias' => [1, 2, 3, 4, 5],
            'abre' => '07:00',
            'cierra' => '17:00',
            'cerrado' => false,
            'activo' => true,
        ];
    }
}
