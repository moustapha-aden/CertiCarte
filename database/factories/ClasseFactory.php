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
            'Tle S1',
            'Tle ES2',
            'Tle SG3',
            '1ère S4',
            '1ère ES5',
            '1ère SG6',
            '2nde 7',
            '2nde 8',
            '2nde 9',
        ];

        return [
            'label' => fake()->unique()->randomElement($classes),
            'year_id' => fake()->numberBetween(1, 3),
        ];
    }
}
