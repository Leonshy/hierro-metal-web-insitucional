<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Faq> */
class FaqFactory extends Factory
{
    protected $model = Faq::class;

    public function definition(): array
    {
        return [
            'pregunta' => fake()->sentence(5).'?',
            'respuesta' => '<p>'.fake()->sentence(25).'</p>',
            'activo' => true,
        ];
    }
}
