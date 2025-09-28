<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classe>
 */
class ClasseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $classes = [
            'Terminale A',
            'Terminale B',
            'Terminale C',
            'Première A',
            'Première B',
            'Première C',
            'Seconde A',
            'Seconde B',
            'Seconde C',
        ];

        return [
            'label' => fake()->unique()->randomElement($classes),
            'year_id' => fake()->numberBetween(1, 3), // Assuming you have 3 school years created in the seeder
        ];
    }
}
