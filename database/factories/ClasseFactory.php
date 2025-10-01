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
        $classTypes = ['Tle S', 'Tle ES', 'Tle L', 'Tle SG', '1ère S', '1ère ES', '1ère L', '1ère SG', '1ère SG', '2nde'];
        $classType = fake()->randomElement($classTypes);
        $classNumber = fake()->numberBetween(1, 10);

        return [
            'label' => $classType.$classNumber,
            'year_id' => fake()->numberBetween(1, 3),
        ];
    }
}
