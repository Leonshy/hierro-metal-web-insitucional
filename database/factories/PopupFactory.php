<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\Popup;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Popup> */
class PopupFactory extends Factory
{
    protected $model = Popup::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->words(3, true),
            'media_id' => Media::factory(),
            'texto_alternativo' => fake()->sentence(4),
            'enlace' => '/contacto',
            'nueva_pestana' => false,
            'donde' => 'todas',
            'frecuencia' => 'sesion',
            'activo' => true,
        ];
    }
}
